<table>
    @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->customer_name }} - {{ $booking->tour_name }}</td>
            <td>
                @can('edit bookings')
                    <a href="/bookings/{{ $booking->id }}/edit">Edit Trip</a>
                @endcan

                @can('process refunds')
                    <button class="text-red-500">Refund Deposit</button>
                @endcan
            </td>
        </tr>
    @endforeach
</table>