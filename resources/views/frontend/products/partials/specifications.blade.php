<div class="specs-table">
    <table>
        <thead>
            <tr>
                <th colspan="2">Technical Specifications</th>
            </tr>
        </thead>
        <tbody>
            @foreach($specifications as $spec)
                <tr>
                    <td>{{ $spec['label'] }}</td>
                    <td>{{ $spec['value'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>