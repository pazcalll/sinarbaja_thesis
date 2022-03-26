<div class="pre-casefolding">
    {{-- <button class="btn btn-primary rounded style-changer" data-url={{url("analytics/pre-casefolding")}}>Case Folding</button>
    <button class="btn btn-outline-primary rounded style-changer" data-url={{url("analytics/pre-punctuation")}}>Punctuation Removal</button> --}}
    <span class="btn btn-primary rounded-0" style="pointer-events: none">User Insert</span>
    <table id="main_table" class="table table-bordered table-hover table-striped" style="width: 100%">
        <thead>
            <tr>
                <th width="10%">No.</th>
                <th>Base</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stringsUser as $key => $row)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$row['base']}}</td>
                    <td>{{$row['result']}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <span class="btn btn-primary rounded-0" style="pointer-events: none">System Data</span>
    <table id="secondary_table" class="table table-bordered table-hover table-striped" style="width: 100%">
        <thead>
            <tr>
                <th width="10%">No.</th>
                <th>Base</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stringsData as $key => $row)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$row['base']}}</td>
                    <td>{{$row['result']}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        $('#main_table').DataTable({
            searching: false,
            serverside:false,
            processing: false,
        })
        $('#secondary_table').DataTable({
            searching: false,
            serverside:false,
            processing: false,
        })
    })
    // $('.style-changer').on('click', function() {
    //     if (!$(this).hasClass("btn-primary")) {
    //         $('.style-changer').addClass('btn-outline-primary')
    //         $('.style-changer').removeClass('btn-primary')
    //         $(this).removeClass('btn-outline-primary')
    //         $(this).addClass('btn-primary')
    //         console.log($(this).data('url'))
    //         $.ajax({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             url: $(this).data('url'),
    //             type: 'POST',
    //             data: {
    //                 string: ""
    //             },
    //             success: (res) => {
    //                 console.log(res)
    //             },
    //             error: (err) => {
    //                 console.error(err)
    //             }
    //         })
    //     }
    // })
</script>