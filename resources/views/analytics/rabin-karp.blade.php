<div class="rabin row" style="overflow-x: auto; overflow-y: hidden; word-wrap: break-word;">
    {{-- <button class="btn btn-primary rounded style-changer" data-url={{url("analytics/pre-casefolding")}}>Case Folding</button>
    <button class="btn btn-outline-primary rounded style-changer" data-url={{url("analytics/pre-punctuation")}}>Punctuation Removal</button> --}}
    @isset($stringsUser)
        @if ($stringsUser != '')
            <span class="btn btn-primary rounded-0" style="pointer-events: none">Masukan Pengguna</span>
            <table id="main_table" class="table table-bordered table-hover table-striped" style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                        <th width="10%">No.</th>
                        <th width="45%">Awalan</th>
                        <th width="45%">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stringsUser as $key => $row)
                        <tr>
                            <td style="word-wrap: break-word; width: 10%">{{$key+1}}</td>
                            <td style="word-wrap: break-word; width: 45%">{{$row['base']}}</td>
                            <td style="word-wrap: break-word; width: 45%">{{$row['result']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endisset

    @isset($stringsData)
        @if ($stringsData != '')
            <span class="btn btn-primary rounded-0" style="pointer-events: none">Data Sistem</span>
            <table id="secondary_table" class="table table-bordered table-hover table-striped" style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                        <th width="10%">No.</th>
                        <th width="45%">Awalan</th>
                        <th width="45%">Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stringsData as $key => $row)
                        <tr>
                            <td style="word-wrap: break-word; width:10%">{{$key+1}}</td>
                            <td style="word-wrap: break-word; width:45%">{{$row['base']}}</td>
                            <td style="word-wrap: break-word; width:45%">{{$row['result']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endisset
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
</script>