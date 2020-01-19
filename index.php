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

function train(){
    print('<br><br>============================================================================================================<br> Training<br>');
    $epochs = 1;
    $learning_rate = 0.05;
    $fungsi_pembelajaran = 0.01;
    $dataset =  [
                 [0,1,1,0, 0], #0=Array ( [0] => 0 [1] => 1 [2] => 1 [3] => 0 [4] => 0 )
                 [0,0,1,1, 1], #1=Array ( [0] => 0 [1] => 0 [2] => 1 [3] => 1 [4] => 1 )
                 [1,1,1,1, 0],
                 [1,0,0,1, 1],
                ];
    $init_weight = [[1,1,1,0],[1,0,1,1]];

    $prev_min = 1;
    

    
    print('<br>Dataset= <br>');
        foreach ($dataset as $x ){
            print('{');
            foreach($x as $val ){
                print($val.' ,  ');
            }
            print('} ');
            print('<br>');
        }
    print('<br>Initial Weight= <br>');
    foreach ($init_weight as $x ){
        print('{');
        foreach($x as $val ){
            print($val.' ,  ');
        }
        print('} ');
        print('<br>');
    }

    for ($epoch=0 ; $epoch<$epochs; $epoch++){
        print('============================================================================================================<br>');
        print('Epoch        : '. $epoch.'<br>');
        print('Learning Rate: '. $learning_rate.'<br><br>');
        $val_err = 0;
        foreach ($dataset as $row => $val){
        
            if ($row == 0 && $epoch == 0 ){ 
                $prev_weight = $init_weight;
            }
            

            #Print Data and Target
            print('<br>');
            print('Data ke-');
            print($row+1);
            if($prev_min == 0){
                $prev_min = 1;
            }else{
                $prev_min = 0;
            }
            print(' dengan target ke-'.$prev_min.' <br>');


            #Find Distance
            $distance_target_0 = euclidean_distance($val,$prev_weight[0]);
            $distance_target_1 = euclidean_distance($val,$prev_weight[1]);
            
            print('distance Class 0 = '.$distance_target_0.'<br>');
            print('distance Class 1 = '.$distance_target_1.'<br>');
            

            #Find Predicted Class
            $min_class = find_minimum($distance_target_0,$distance_target_1);
            print('Predicted class= ');
            print($min_class);
            print(' | ');

            #Check if min_class true
            if($min_class == $val[4]){
                print('Real class       = '.$val[4]);
                print(' || TRUE<BR>');
            }else{
                print('Real class       = '.$val[4]);
                print(' || FALSE<BR>');
                $val_err += 1;
            }
            

            #update weight
            $array_weight_updated = weight_update($learning_rate, $prev_weight[$min_class], $val);
    
            if ($min_class == 0){
                array_shift($prev_weight);
                array_unshift($prev_weight, $array_weight_updated);
            }else{
                array_pop($prev_weight);
                array_push($prev_weight, $array_weight_updated);
            }
    
            print('Updated Weight : <br>');
            foreach ($prev_weight as $x ){
                print('{');
                foreach($x as $val ){
                    print($val.' ,  ');
                }
                print('} ');
            }
            print('<br>');
        }

        $learning_rate = $learning_rate * $fungsi_pembelajaran;
        $val_err = $val_err / sizeof($dataset) * 100;
        print ('<br>Error = '.$val_err.'%<br>');
    }
    return $prev_weight;
}


function test($final_weight){
    print('<br><br>============================================================================================================<br> Testing<br>');
    print('============================================================================================================<br>');
    
    print('Final Weight : <br>');
    foreach ($final_weight as $x ){
        print('{');
        foreach($x as $val ){
            print('<b>'.$val.'</b>'.' ,  ');
        }
        print('} ');
    }
    print('<br>');

    

    
    $test_set = [[0,0,1,0],[1,0,0,1]];

    print('<br><br>');
    foreach ($test_set as $row => $val){

        print('Input= {');
        foreach ($val as $x =>$value){
            print($value.', ');
        }
        print('}<br>');
        
        $distance_target_0 = euclidean_distance($val,$final_weight[0]);
        $distance_target_1 = euclidean_distance($val,$final_weight[1]);
        
        print('distance Class 0 = '.$distance_target_0.'<br>');
        print('distance Class 1 = '.$distance_target_1.'<br>');
    
        $min_class = find_minimum($distance_target_0,$distance_target_1);
        print('chosen class :');
        print($min_class);
        print('<br><br>');


    }


}



$final_weight = train();
test($final_weight);

?>