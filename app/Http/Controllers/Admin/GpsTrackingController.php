<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class GpsTrackingController extends Controller
{
    /**
     * Titik koordinat dummy di Bali.
     */
    private const BALI_POINTS = [
        ['city' => 'Denpasar', 'top' => 63, 'left' => 63],
        ['city' => 'Sanur', 'top' => 71, 'left' => 70],
        ['city' => 'Kuta', 'top' => 66, 'left' => 49],
        ['city' => 'Seminyak', 'top' => 61, 'left' => 45],
        ['city' => 'Canggu', 'top' => 55, 'left' => 38],
        ['city' => 'Jimbaran', 'top' => 76, 'left' => 50],
        ['city' => 'Nusa Dua', 'top' => 84, 'left' => 61],
        ['city' => 'Ubud', 'top' => 40, 'left' => 61],
        ['city' => 'Gianyar', 'top' => 49, 'left' => 67],
        ['city' => 'Tabanan', 'top' => 46, 'left' => 35],
    ];

    public function index()
    {
        $bookings = Booking::with([
                'user',
                'motorcycle',
                'motorcycleStock',
            ])
            ->where('status', Booking::STATUS_ONGOING)
            ->get();

        $trackings = [];

        foreach ($bookings as $index => $booking) {

            $point = self::BALI_POINTS[$index % count(self::BALI_POINTS)];

            $trackings[] = [
                'booking_id' => $booking->id,

                'customer' => $booking->user->name,

                'motorcycle' => $booking->motorcycle->brand .
                    ' ' .
                    $booking->motorcycle->model,

                'plate_number' => optional($booking->motorcycleStock)->plate_number
                    ?? '-',

                'city' => $point['city'],

                'top' => $point['top'],

                'left' => $point['left'],

                'speed' => rand(18, 58),

                'status' => 'Online',

                'updated_at' => now()->format('H:i'),
            ];
        }

        /**
         * Jika belum ada rental yang berjalan,
         * tampilkan dummy agar halaman GPS tetap menarik.
         */
        if (empty($trackings)) {

            $dummy = [
                [
                    'customer' => 'Budi Santoso',
                    'motorcycle' => 'Yamaha NMAX',
                    'plate_number' => 'DK 1234 AA',
                ],
                [
                    'customer' => 'Andi Saputra',
                    'motorcycle' => 'Honda PCX',
                    'plate_number' => 'DK 5678 BB',
                ],
                [
                    'customer' => 'Dewi Lestari',
                    'motorcycle' => 'Honda Vario 160',
                    'plate_number' => 'DK 9012 CC',
                ],
            ];

            foreach ($dummy as $i => $item) {

                $point = self::BALI_POINTS[$i];

                $trackings[] = [

                    'booking_id' => null,

                    'customer' => $item['customer'],

                    'motorcycle' => $item['motorcycle'],

                    'plate_number' => $item['plate_number'],

                    'city' => $point['city'],

                    'top' => $point['top'],

                    'left' => $point['left'],

                    'speed' => rand(22, 47),

                    'status' => 'Online',

                    'updated_at' => now()->format('H:i'),
                ];
            }
        }

        return view('admin.gps.index', [
            'trackings' => $trackings,
        ]);
    }
}