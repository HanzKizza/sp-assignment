<h5>Proof read this data</h5>
<table class="table text-white">
    @php 
        $data = json_decode($data);
    
        $counter = 0;
        foreach($data as $dataset){
            if(sizeof($dataset) == 3){
                if($counter == 0){
                    echo "<thead>"; 
                        echo "<tr>"; 
                            echo "<th>".$dataset[0]."</th>";
                            echo "<th>".$dataset[1]."</th>";
                            echo "<th>".$dataset[2]."</th>";
                        echo "</tr>";
                    echo "</thead>";
                }
                else{
                    echo "<tr>"; 
                        echo "<td>".$dataset[0]."</td>";
                        echo "<td>".$dataset[1]."</td>";
                        echo "<td>".$dataset[2]."</td>";
                    echo "</tr>";
                }
            }
            $counter++;
        }
    @endphp 
</table>
