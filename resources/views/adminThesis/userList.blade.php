<div style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <div class="container">
        <h3>Users</h3>
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
                        <td style="word-wrap: break-word; width: 30%">
                            <button class="btn btn-danger" onclick="modalDeleteUser('{{$user->id}}', '{{$user->name}}')"><li class="icon md-delete"></li></button>
                            <button class="btn btn-info" onclick="modalChangeGroupUser('{{$user->id}}', '{{$user->name}}')"><li class="icon md-group"></li></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('adminThesis.modal.modalDeleteUser')
@include('adminThesis.modal.modalSetGroupUser')

<script>
    $(document).ready(function() {
        $('#main_table').DataTable()
    })
    function modalDeleteUser(id, name) {
        $('#modalDeleteUser').modal('show')
        $('.user-name-div').html(`<h4>${name}</h4>`)
        $('#user-id').val(id)
    }
    function modalChangeGroupUser(id, name) {
        $('#modalSetGroupUser').modal('show')
        $.ajax({
            url: `{{url("dashboard/get-group-user")}}/${id}`,
            type: 'GET',
            success: (res) => {
                let group = ''
                res.forEach(segment => {
                    segment.forEach(item =>{
                        group += `
                            <option value="${item.id}">${item.group_name}</option>
                        `
                    })
                })
                $("#select-group-user").html(group)
                $(".user-name-div h4").html(name)
                $("#user-id").val(id)
            }
        })
    }
    $('#deleteUser').on('submit', function(e) {
        e.preventDefault()
        $.ajax({
            url: '{{route("deleteUser")}}',
            type: 'POST',
            async: false,
            data: $(this).serialize(),
            success: (res) =>{
                $('#modalDeleteUser').modal('hide')
                $('#modalDeleteUser').on('hidden.bs.modal', function () {
                    $('.users-feature').click()
                })
            },
            error: (err) => {
                console.error(err)
            }
        })
    })
    $('#setGroupUser').on('submit', function(e) {
        e.preventDefault()
        $.ajax({
            url: '{{route("setGroupUser")}}',
            type: 'POST',
            async: false,
            data: $(this).serialize(),
            success: (res) =>{
                $('#modalSetGroupUser').modal('hide')
                $('#modalSetGroupUser').on('hidden.bs.modal', function () {
                    $('.users-feature').click()
                })
            },
            error: (err) => {
                console.error(err)
            }
        })
    })
</script>