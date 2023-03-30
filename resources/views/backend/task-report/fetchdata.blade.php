<table class="table table-bordered" style="padding-top: 10px;" id="taskReportTable">
    <thead>
        <tr>
            <th scope="col">S.No.</th>
            <th scope="col">Employee Name</th>
            <th scope="col">Assigned Task</th>
            <th scope="col">Completed Task</th>
            <th scope="col">Pending Task</th>
            <th scope="col">Not Started Task</th>
            <th scope="col">Achieved Task Point</th>
            <th scope="col">Achieved Extra Points</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($report as $item)
        <?php $item = (object)$item; ?>
            <tr>
                <th scope="row">{{$loop->iteration}}</th>
                <td>{{$item->staffname}}</td>
                <td>{{$item->assignedTasks}}</td>
                <td>{{$item->completedTasks}}</td>
                <td>{{$item->pendingTasks}}</td>
                <td>{{$item->notstartedTasks}}</td>
                <td>{{$item->achievedPoints}}</td>
                <td>{{$item->extrapoints}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    $("#taskReportTable").dataTable({
        "paging": false,
        "ordering": false,
        "bInfo" : false,
    });
</script>