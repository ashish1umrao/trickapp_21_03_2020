<table class="table border-none">
    <tbody>
        <tr>
            <td>
                <?php if($showdata <> ""): $i=1; foreach($showdata as $eventi):   //echo "<pre>"; print_r($showdata); die; ?>
                    <table class="table border-none">
                        <thead>
                            <tr>
                                <th align="left" colspan="3"><strong><?=stripslashes($eventi['purpose'])?></strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th align="left" colspan="3"><strong>To Date</strong></th>
                                <td align="left" width="33%"><?=stripslashes($eventi['from_date'])?></td>
                            </tr>
                            <tr>
                                <th align="left" colspan="3"><strong>From Date</strong></th>
                                <td align="left" width="33%"><?=stripslashes($eventi['to_date'])?></td>
                                </tr>
                                <tr>  
                                <th align="left" colspan="3"><strong>Time</strong></th>
                                <td align="left" width="33%"><?=stripslashes($eventi['time'])?></td>
                            </tr>
                           
                        </tbody>
                    </table>
                <?php endforeach; else: ?>
                Data not found.
                <?php endif; ?>
            </td>
        </tr>                   
    </tbody>
</table>