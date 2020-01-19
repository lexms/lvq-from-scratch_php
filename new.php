<?php


function euclidean_distance($row1, $row2) {
    $distance = 0.0;
    $row_length = sizeof($row1)-1; #target class excluded
    for ($i=0; $i < $row_length; $i++){
        $distance += ($row1[$i]-$row2[$i])**2;
    }
    return sqrt($distance);
}


function find_minimum($distance_target_0, $distance_target_1){
    $chosen_class = 0;
    #find minimum
    if ($distance_target_0 <= $distance_target_1){
        $chosen_class = 0; 
    } else{
        $chosen_class = 1;
    }
    return $chosen_class;
}

function weight_update($learning_rate, $chosen_class, $data_to_update){
    
    $lr= $learning_rate;
    $updated_weight = array();
    $updated_weights = array();
    $weight = 0;
    
    $row_length = sizeof($chosen_class);

    for ($i=0; $i < $row_length; $i++){
        $weight = $chosen_class[$i] + ($lr * ($data_to_update[$i]-$chosen_class[$i]));
        #print('weight: '. $weight.'<br>');
        array_push($updated_weight, $weight);
        #print_r($updated_weight);
        #print('<br>'); 
    }
    
    return $updated_weight;
    
}

function main(){
    $learning_rate = 0.05;
    $dataset =  [
                 [0,1,1,0, 0], #0=Array ( [0] => 0 [1] => 1 [2] => 1 [3] => 0 [4] => 0 )
                 [0,0,1,1, 1], #1=Array ( [0] => 0 [1] => 0 [2] => 1 [3] => 1 [4] => 1 )
                 [1,1,1,1, 0],
                 [1,0,0,1, 1]
                ];
    $init_weight = [[1,1,1,0],[1,0,1,1]];
    foreach ($dataset as $row => $val){
        #print($row.'=');
        #print_r($val);
        #print('<br>');
        
        if ($row == 0 ){ 
            $prev_weight = $init_weight;
        }
        #print('before : <br>');
        #print_r($prev_weight);
        print('<br>');

        $distance_target_0 = euclidean_distance($val,$prev_weight[0]);
        $distance_target_1 = euclidean_distance($val,$prev_weight[1]);
        
        print('distance Class 0 = '.$distance_target_0.'<br>');
        print('distance Class 1 = '.$distance_target_1.'<br>');

        $min_class = find_minimum($distance_target_0,$distance_target_1);
        print('chosen class :');
        print($min_class);
        print('<br>');
        
        $array_weight_updated = weight_update($learning_rate, $prev_weight[$min_class], $val);

        if ($min_class == 0){
            array_shift($prev_weight);
            array_unshift($prev_weight, $array_weight_updated);
        }else{
            array_pop($prev_weight);
            array_push($prev_weight, $array_weight_updated);
        }

        print('Updated Weight : <br>');
        print_r($prev_weight);
        print('<br>');

        




    }
}

main()


?>