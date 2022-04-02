<table id="main_table" class="table table-bordered table-hover table-striped" style="width: 100%; table-layout: fixed;">
    <thead>
        <tr>
            <th width="10%">No.</th>
            <th width="30%">User Name</th>
            <th width="30%">Email</th>
            <th width="30%">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td style="word-wrap: break-word; width: 10%">{{$loop->iteration}}</td>
                <td style="word-wrap: break-word; width: 30%">{{$user->name}}</td>
                <td style="word-wrap: break-word; width: 30%">
                    @if (isset($user->email))
                        {{$user->email}}
                    @else
                        _
                    @endif
                </td>
                <td style="word-wrap: break-word; width: 30%"><button class="btn btn-danger" onclick="">Delete</button></td>
            </tr>
        @endforeach
    </tbody>
</table>