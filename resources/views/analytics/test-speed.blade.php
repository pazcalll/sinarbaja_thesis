<div class="test" style="overflow-x: auto; overflow-y: hidden; word-wrap: break-word; padding: 20px">
    <table class="table-bordered table-hover table-striped" style="width: 100%;">
        <thead>
            <tr style="background-color: #696969; color: #fff;">
                <th></th>
                <th><b>Number of Rows</b></th>
                <th><b>Duration</b></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<script>
    function speed(str, speedUrl) {
        let ajaxTime= new Date().getTime();
        $.ajax({
            url: `{{url("analytics/speed")}}/`+speedUrl,
            type: 'get',
            async: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                string:str
            },
            success:(res)=>{
                let totalTime = new Date().getTime()-ajaxTime
                let speedTr = ''
                speedTr += `
                    <tr>
                        <td>${speedUrl}</td>
                        <td>${res.data.length}</td>
                        <td>${totalTime} ms</td>
                    </tr>
                `
                $('table tbody').append(speedTr)
            },
            error: (err) => {
                console.error(err)
            }
        })
    }
    $(document).ready(function() {
        speed('{{$string}}', 'sql')
        speed('{{$string}}', 'rabin')
    })
</script>