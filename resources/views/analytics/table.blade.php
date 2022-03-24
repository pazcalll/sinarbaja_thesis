
<table id="main_table">
    <thead>
        <tr>
            @foreach ($columns as $column)
                <td>{{$column}}</td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                @foreach ($row as $item)
                    <td>{{$item}}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>