<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:30'],
            'current_address' => ['required', 'string', 'max:1000'],
            'passport_number' => ['required', 'string', 'max:100'],
            'origin_country' => ['required', 'string', 'max:100'],
            'has_license' => ['required', 'boolean'],

            'passport_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'visa_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'license_file' => ['required_if:has_license,1', 'nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'digital_signature' => ['required', 'string'],

            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'passport_file.required' => 'Upload paspor wajib diisi.',
            'visa_file.required' => 'Upload visa wajib diisi.',
            'license_file.required_if' => 'Upload SIM wajib diisi jika memilih memiliki SIM.',
            'digital_signature.required' => 'Tanda tangan digital wajib diisi.',
        ]);

        $user = DB::transaction(function () use ($request, $validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'current_address' => $validated['current_address'],
                'passport_number' => $validated['passport_number'],
                'origin_country' => $validated['origin_country'],
                'has_license' => (bool) $validated['has_license'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->forceFill([
                'role' => 'customer',
                'status' => 'active',
            ])->save();

            $this->storeUploadedDocument(
                user: $user,
                file: $request->file('passport_file'),
                type: UserDocument::TYPE_PASSPORT
            );

            $this->storeUploadedDocument(
                user: $user,
                file: $request->file('visa_file'),
                type: UserDocument::TYPE_VISA
            );

            if ($request->hasFile('license_file')) {
                $this->storeUploadedDocument(
                    user: $user,
                    file: $request->file('license_file'),
                    type: UserDocument::TYPE_LICENSE
                );
            }

            $this->storeSignatureDocument(
                user: $user,
                signatureData: $validated['digital_signature']
            );

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function storeUploadedDocument(User $user, UploadedFile $file, string $type): void
    {
        $path = $file->store("user-documents/{$user->id}", 'public');

        $user->documents()->create([
            'type' => $type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'uploaded_at' => now(),
        ]);
    }

    private function storeSignatureDocument(User $user, string $signatureData): void
    {
        if (! str_starts_with($signatureData, 'data:image/png;base64,')) {
            throw ValidationException::withMessages([
                'digital_signature' => 'Format tanda tangan digital tidak valid.',
            ]);
        }

        $encoded = substr($signatureData, strpos($signatureData, ',') + 1);
        $decoded = base64_decode($encoded, true);

        if ($decoded === false) {
            throw ValidationException::withMessages([
                'digital_signature' => 'Tanda tangan digital gagal diproses.',
            ]);
        }

        if (strlen($decoded) > 1024 * 1024) {
            throw ValidationException::withMessages([
                'digital_signature' => 'Ukuran tanda tangan digital terlalu besar.',
            ]);
        }

        $path = 'user-documents/' . $user->id . '/signature-' . Str::uuid() . '.png';

        Storage::disk('public')->put($path, $decoded);

        $user->documents()->create([
            'type' => UserDocument::TYPE_SIGNATURE,
            'file_path' => $path,
            'original_name' => 'digital-signature.png',
            'mime_type' => 'image/png',
            'size' => strlen($decoded),
            'uploaded_at' => now(),
        ]);
    }
}