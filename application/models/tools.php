<?php

function cmpClubRes($resA, $resB) {
    if ($resA['points_count'] == $resB['points_count']) {
        $diff = $resA['balls_balance_plus'] - $resA['balls_balance_minus'] - $resB['balls_balance_plus'] + $resB['balls_balance_minus'];
        if ($diff == 0) {
            return 0;
        }
        return (($diff > 0) ? -1 : 1);
    }
    return (($resA['points_count'] > $resB['points_count']) ? -1 : 1);
}

?>
