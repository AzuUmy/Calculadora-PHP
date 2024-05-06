<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>




    <div class="Calculator">
        <form action="" method="get">
        <div>
            <div class="top">
                Calculadora PHP
            </div>
            <div>
                <input class="visor" type="text" readonly>
            </div>
            
            <div>
                <input class="inputs" type="number" name="number1" placeholder="Numero 1">

                <select class="selects" name="mathOption">
                    <option value="+">+</option>
                    <option value="-">-</option>
                    <option value="*">x</option>
                    <option value="/">÷</option>
                </select>

                <input class="inputs" type="number" name="number2" placeholder="Numero 2">
            </div>

            <div class="buttoes">
                <button class="ButtoesInside" type="submit" name="calculate" value="calculate" >=</button>
                <button class="ButtoesInside" type="submit" name="saveValue" value="salvar">Salvar</button>
                <button class="ButtoesInside" type="submit" name="getValue" value="pegar" >Pegar Valores</button>
                <button class="ButtoesInside" type="submit" name="saveAndGetValue" value="saveAndGet">C</button>
                <button class="ButtoesInside" type="submit" name="cleanHistory" value="cleanHistory"> Limpar Historico</button>
            </div>
            <div class="aditionalButtons">
                <button class="ButtoesInside" type="submit" name="fatoracao" name="fatoracao" value="calculateFatoracao" > Calculo de Fatoracao</button>
                <input  class="inputs" type="number" name="recivePotencia" placeholder="selecione a potencia">
                <button class="ButtoesInside" type="submit" name="potencia" name="potencia" value="calculatePotencia" > Calculo de potencia</button>
            </div>
                

            <div class="showHistory">
                <P>Historico</P>
                <?php
                session_start();

                if (!isset($_SESSION['historico'])) {
                  $_SESSION['historico'] = array();
                }


                if (isset($_GET['calculate']) && $_GET['calculate'] === 'calculate') {
                    calculate();
                }else if(isset($_GET['saveValue']) && $_GET['saveValue'] === 'salvar'){
                    salvar();
                }elseif(isset($_GET['getValue']) && $_GET['getValue'] === 'pegar'){
                    pegar();
                }else if(isset($_GET['saveAndGetValue']) && $_GET['saveAndGetValue'] === 'saveAndGet'){
                     saveAndGet();
                }else if(isset($_GET['cleanHistory']) && $_GET['cleanHistory'] === 'cleanHistory'){
                    clearHistory();
                }else if(isset($_GET['fatoracao']) && $_GET['fatoracao'] === 'calculateFatoracao'){
                     fatoracao();
                } else if(isset($_GET['potencia']) && $_GET['potencia'] === 'calculatePotencia'){
                    potenciacao();
                }
                
                
                function calculate(){
                    $number1 = $_GET['number1'];
                    $number2 = $_GET['number2'];
                    $mathOption = $_GET['mathOption'];
                
                    $expression = $number1 . $mathOption . $number2;
                
                    eval('$resultado = ' . $expression . ';');
                
                    $_SESSION['expression'] = $expression;
                    $_SESSION['result'] = $resultado;
                
                    echo "<script>document.querySelector('.visor').value = '{$expression} = {$resultado}';</script>";
                  
                    $_SESSION['historico'][] = array('expression' => $expression, 'result' => $resultado);
                }
                
                function salvar(){
                    $ex = $_SESSION['expression'];
                    $re = $_SESSION['result'];
                
                    $_SESSION['getEx'] = $ex;
                    $_SESSION['getRE'] = $re;
                }
                
                
                
                
                function pegar(){
                    $ex = $_SESSION['getEx'];
                    $re = $_SESSION['getRE'];
                    
                    echo "<script>document.querySelector('.visor').value = '{$ex} = {$re}';</script>";
                }
                
                function saveAndGet() {
                    if (isset($_SESSION['expression']) && isset($_SESSION['result'])) {
                
                        $newExpression = $_SESSION['expression'];
                        $newResult = $_SESSION['result'];
                
                        if (isset($_SESSION['oldExpression']) && isset($_SESSION['oldResult'])) {
                            $oldExpression = $_SESSION['oldExpression'];
                            $oldResult = $_SESSION['oldResult'];
                
                            // Compare old and new values
                            if ($oldExpression !== $newExpression || $oldResult !== $newResult) {
                                // Values are different, update session with new values
                                $_SESSION['getNewEx'] = $newExpression;
                                $_SESSION['getNewRE'] = $newResult;
                            } 
                            
                            else {
                                // Values are same, print them
                                echo "<script>document.querySelector('.visor').value = '{$newExpression} = {$newResult}';</script>";
                                $_SESSION['getNewEx'] = $newExpression;
                                $_SESSION['getNewRE'] = $newResult;
                                unset($_SESSION['oldResult']);
                                unset($_SESSION['oldExpression']);
                
                            }
                        } else {
                            $_SESSION['getNewEx'] = $newExpression;
                            $_SESSION['getNewRE'] = $newResult;
                        }
                
                        $_SESSION['oldExpression'] = $newExpression;
                        $_SESSION['oldResult'] = $newResult;
                    }
                }
                
                
                function fatoracao(){

                    if(isset($_SESSION['result'])){
                        $resultado = $_SESSION['result'];
                        $factors = factorization($resultado);
                        $factorsString = implode(', ', $factors);
                        echo "<script>document.querySelector('.visor').value += 'Fatoração: $factorsString ';</script>";
                    }
                }

                function factorization($number){
                    $factors = [];
                    for ($i = 1; $i <= $number; $i++) {
                        if ($number % $i == 0) {
                            $factors[] = $i;
                        }
                    }
                    return $factors;
                }


                function potenciacao(){
                    $potenciacao = $_GET['recivePotencia'];
                    if(isset($_SESSION['result'])){
                        $base = $_SESSION['result'];
                        $exponente = $potenciacao;
                        $result = pow($base, $exponente);
                        echo "<script>document.querySelector('.visor').value += 'Potenciacao: $result ';</script>";
                        return $result;
                    } else {
                        return null; 
                    }
                }

                function clearHistory(){
                    unset($_SESSION['historico']);
                    $_SESSION['historico'] = array();
                }

                function History(){
                    if(isset($_SESSION['historico'])) {
                        echo "<div class='history-content'>";
                        echo  "<div class='history'>";
                        foreach($_SESSION['historico'] as $historico) {
                            echo  $historico['expression'] . " = " . $historico['result'] . "<br>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                }

                History();

                ?>
            </div>
           
        </div>

    </form>
      
    </div>
    


         
         





<style>

    .top{
        text-align: center;
        font-size: 2em;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
    .Calculator{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 2em;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .visor{
            padding: 3em;
            width: 945px;
            border: none;
            border-radius: 1em;
            filter: drop-shadow(0 0 0.55rem rgba(0, 0, 0, 0.082));
            margin-bottom: 1em;
            margin-top: 1em;
            outline: none;
            font-size: 20px;
        }

        .buttoes{
            padding: 1em 0;
            display: flex;
            gap: 1em;
   
        }

        .ButtoesInside{
            border: none;
            padding: 1em;
            background: white;
            filter: drop-shadow(0 0 0.55rem rgba(0, 0, 0, 0.082));
            border-radius: 1em;
            font-size: 20px;
            width: 200px;
         
        }

        .inputs{
            border: none;
            font-size: 20px;
            padding: 1em;
            filter: drop-shadow(0 0 0.55rem rgba(0, 0, 0, 0.082));
            border-radius: 1em;
            text-align: center;
            width: 325px;
            outline: none;
        }

        .selects{
            border: none;
            font-size: 20px;
            padding: 1em;
            filter: drop-shadow(0 0 0.55rem rgba(0, 0, 0, 0.082));
            border-radius: 1em;
            text-align: center;
            width: 325px;
            appearance: none;
            -moz-appearance: none;
            -webkit-appearance: none;
            outline: none;
        }

        .history{
            font-size: 25px;
            margin: 1em;

        }

    .history-content{
            overflow: auto;
            max-height: 250px;
            border-radius: 1em;
            filter: drop-shadow(0 0 0.55rem rgba(0, 0, 0, 0.082));
            background-color: white;
        }

    .history-content::-webkit-scrollbar {
         width: 12px; /* Set the width of the scrollbar */
         height: 12px; /* Set the height of the scrollbar */
        }

    .history-content::-webkit-scrollbar-thumb {
         background-color: #888; /* Set the color of the scrollbar thumb */
         border-radius: 6px; /* Add border radius for rounded scrollbar thumb */
    }

    .history-content::-webkit-scrollbar-thumb:hover {
         background-color: #555; /* Change the color of the scrollbar thumb on hover */
    }

    .aditionalButtons{
        display: flex;
        gap: 1em;

    }



</style>
</body>
</html>