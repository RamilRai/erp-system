<table class="table table-bordered" style="padding-top: 10px;" id="taskReportTable">
    <thead>
        <tr style="background: #5461f7; color: #fff">
            <th scope="col">S.No.</th>
            <th scope="col">Employee Name</th>
            <th scope="col">Assigned Task</th>
            <th scope="col">Completed Task</th>
            <th scope="col">Pending Task</th>
            <th scope="col">Not Started Task</th>
            <th scope="col">Hold / Cancelled Task</th>
            <th scope="col">Achieved Task Point</th>
            <th scope="col">Achieved Extra Task Point</th>
            <th scope="col">Achieved Total Point</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($report as $item)
            <?php
                $item = (object)$item;
                $cssStyles = '';
                if($loop->iteration % 2 == 0){
                    $cssStyles = 'background: #e7e9ff; ';
                }
            ?>
            <tr style="{{$cssStyles}}">
                <th scope="row">{{$loop->iteration}}</th>
                <td>{{$item->staffname}}</td>
                <td>{{$item->assignedTasks}}</td>
                <td>{{$item->completedTasks}}</td>
                <td>{{$item->pendingTasks}}</td>
                <td>{{$item->notstartedTasks}}</td>
                <td>{{$item->cancelHoldTasks}}</td>
                <td>{{$item->achievedPoints}}</td>
                <td>{{$item->extraPoints}}</td>
                <td>{{$item->totalPoints}}</td>
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
