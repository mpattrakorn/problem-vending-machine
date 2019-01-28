<!DOCTYPE html>
<html>

<head>
    <title>Vending Machine</title>
    <script src="vue.js"></script>

    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/js/uikit.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/js/uikit-icons.min.js"></script>

</head>

<body>

    <?php

        //get data from API

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://www.mocky.io/v2/5af11f8c3100004d0096c7ed",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);

        $products = json_decode($response, true);
    
    ?>



        <div id="app" class="uk-text-center uk-margin-medium-top" uk-grid>


            <div class="uk-width-1-5@m"></div>
            <div class="uk-card uk-card-default uk-card-default uk-card-body uk-width-3-5@m">

                <h2>Vending Machine</h2>

                <p>Please Insert Coins</p>

                <button class="uk-button uk-button-default uk-button-small" v-on:click="insert(1)" :disabled="disabled">1 thb</button>
                <button class="uk-button uk-button-default uk-button-small" v-on:click="insert(2)" :disabled="disabled">2 thb</button>
                <button class="uk-button uk-button-default uk-button-small" v-on:click="insert(5)" :disabled="disabled">5 thb</button>
                <button class="uk-button uk-button-default uk-button-small" v-on:click="insert(10)" :disabled="disabled">10 thb</button>



                <div class="uk-margin">
                    <p> Coin: {{coins}} </p>
                    <span>Total: {{ pay }}</span> <span>THB</span>
                </div>

                <p>{{ message }} </p>
                <p>{{ change }}</p>
                <p>{{ change_coins }}</p>
                <p>{{ refund_coin }}</p>

                <button class="uk-button uk-button-default uk-button-small" v-on:click="refund" :disabled="disabled">Refund</button>
                <button class="uk-button uk-button-danger uk-button-small" v-on:click="clear">Clear</button>

                <div class="uk-child-width-1-3@s uk-grid-match" uk-grid>

                    <?php 

                    foreach($products['data'] as $product){
                    
                    ?>

                    <div>
                        <div class="uk-card uk-card-default uk-card-hover uk-card-body">
                            <h4> <?php echo $product['name']; ?></h4>
                            <img data-src="<?php echo $product['image'];  ?>"uk-img>
                            <p>Price: <?php echo $product['price']; ?> </p>

                            <?php
                            //status of product
                            if($product['in_stock']){
                                echo "<button class='uk-button uk-button-primary uk-button-small' v-on:click='buy(".$product['price'].")'> Purchase </button>";
                            } else {
                                echo "<button class='uk-button uk-button-default uk-button-small' disabled> Out of stock </button>";
                            }
                            ?>
                            
                        </div>
                    </div>

                    <?php
                    }
                    ?>


                </div>

            </div>

            <div class="uk-width-1-5@m"></div>

        </div>

        <!---<script src="app.js"></script>-->

</body>

<script>
    var app = new Vue({
        el: '#app',
        data: {
            message: '',
            pay: 0,
            change: '',
            coins: '',
            change_coins: '',
            disabled: false,
            refund_coin: ''
        },
        methods: {
            insert: function(coin) {
                this.coins += coin+" ";
                this.pay = this.pay + coin;
            },
            clear: function() {
                this.pay = 0;
                this.message = '';
                this.change = '';
                this.coins = '';
                this.change_coins = '';
                this.refund_coin = '';
                this.disabled = false;
            },
            refund: function() {
                this.refund_coin = "Refund: "+this.coins;
                this.pay = 0;
                this.message = '';
                this.change = '';
                this.coins = '';
                this.change_coins = '';
                this.disabled = true;
            },
            buy: function(price){

                this.disabled = true;
                var result = this.pay - price;
                
                
                if(result>=0){
                    
                    this.message = 'Got it!'
                    
                    this.change_coins = "Coins return: ";
                    this.change = 'Your change: '+ result +' THB';
                    
                    while(result != 0){
                        

                        if(result>=10){
                            result = result - 10;
                            this.change_coins += 10 + " ";
                        } else if(result < 10 && result >= 5){
                            result = result - 5;
                            this.change_coins += 5 + " ";
                        } else if (result < 5 && result >= 2) {
                            result = result - 2;
                            this.change_coins += 2 + " ";
                        } else {
                            result = result - 1;
                            this.change_coins += 1 + " ";
                        }

                    }

                } else {
                    this.message = 'Money is not enough!'
                }
            }
        }
    })
</script>

</html>