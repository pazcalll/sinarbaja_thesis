<div style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <div class="container">
        <h3>Users</h3>
        <table id="main_table" class="table table-bordered table-hover table-striped" style="width: 100%; table-layout: fixed;">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="25%">User Name</th>
                    <th width="15%">Group Name</th>
                    <th width="25%">Email</th>
                    <th width="30%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td style="word-wrap: break-word; width: 5%">{{$loop->iteration}}</td>
                        <td style="word-wrap: break-word; width: 30%">{{$user->name}}</td>
                        <td style="word-wrap: break-word; width: 10%">{{$user->group_name}}</td>
                        <td style="word-wrap: break-word; width: 25%">
                            @if (isset($user->email))
                                {{$user->email}}
                            @else
                                _
                            @endif
                        </td>
                        <td style="word-wrap: break-word; width: 30%">
                            <button class="btn btn-danger" onclick="modalDeleteUser('{{$user->id}}', '{{$user->name}}')"><li class="icon md-delete icon-lg"></li></button>
                            <button class="btn btn-warning" onclick="modalChangeGroupUser('{{$user->id}}', '{{$user->name}}')"><li class="icon md-group-work icon-lg"></li></button>
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
        $('#modalSetGroupUser .btn-primary').hide()
        $('#modalSetGroupUser').modal('show')
        $.ajax({
            url: `{{url("dashboard/get-group-user")}}/${id}`,
            type: 'GET',
            success: (res) => {
                let group = ''
                let comparator = 0
                res.forEach(segment => {
                    segment.forEach(item =>{
                        if (comparator == 0) comparator = item.id
                        group += `
                            <option value="${item.id}">${item.group_name}</option>
                        `
                    })
                })
                $("#select-group-user").html(group)
                $('#select-group-user').unbind()
                $(".user-name-div h4").html(name)
                $("#user-id-group-edit").val(id)
                $('#select-group-user').on('change', function() {
                    if (comparator == this.value) $('#modalSetGroupUser .btn-primary').hide()
                    else $('#modalSetGroupUser .btn-primary').show()
                })
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
        let fd = $(this).serialize();
        $('#modalSetGroupUser .modal-dialog .modal-content .modal-body').html('Loading, Please Wait...')
        $('#modalSetGroupUser .modal-dialog .modal-content .modal-footer').html('')
        $.ajax({
            url: '{{route("setGroupUser")}}',
            type: 'POST',
            data: fd,
            cache: false,
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