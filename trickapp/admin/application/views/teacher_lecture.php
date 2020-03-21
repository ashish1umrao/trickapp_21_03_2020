<table class="table border-none">
    <tbody>
        <tr>
            <td>
                <?php if($event <> ""): $i=1; foreach($event as $eventi): ?>
                    <table class="table border-none">
                        <thead>
                            <tr>
                                <th align="left" colspan="3"><strong>Lecture <?=$i++?></strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="left" width="33%"><?=stripslashes($eventi['course_name'])?></td>
                                <td align="left" width="33%"><?=stripslashes($eventi['course_semester'])?></td>
                                <td align="left" width="33%"><?=stripslashes($eventi['course_section_name'])?></td>
                            </tr>
                            <tr>
                                <td align="left" width="33%"><?=stripslashes($eventi['subject_name'])?></td>
                                <td align="left" width="33%"><?=$date?></td>
                                <td align="left" width="33%"><?=stripslashes($eventi['period_name'].' period '.$eventi['period_start_time'].' to '.$eventi['period_end_time'])?></td>
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