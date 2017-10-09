<template>
    <div class="container">
        <div class="row">
            <input type="text" class="form-control col-md-3" v-model="filter"/>
        </div>
        <div class="row">
            <div class="col-xs-2 bg-primary">Name</div>
            <div class="col-xs-4 bg-primary text-center">Cur.Price</div>
            <div class="col-xs-4 bg-primary text-center">Avg.Price</div>
            <div class="col-xs-2 bg-primary text-center">% Change(24h)</div>
        </div>
        <div>
            <div v-for="coin in filterCoins" :key="coin.name">
                <div class="row" v-bind:class="{ 'bg-success': coin.up, 'bg-danger': coin.down, 'bg-info': coin.equal, 'hidden': coin.hidden }">
                    <div class="col-xs-2">
                        <strong>{{coin.name}}</strong>
                    </div>
                    <div class="col-xs-4 text-center">
                        {{coin.value}}
                    </div>
                    <div class="col-xs-4 text-center">
                        {{coin.avg}}
                    </div>
                    <div class="col-xs-2 text-center">
                        {{coin.rel}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                coins: [],
                filter: ''
            };
        },
        computed: {
            reorder: function() {
                return this.coins;
            },
            filterCoins: function() {
                const re = new RegExp( '^' + this.filter + '.*');
                return this.coins.filter(function(coin) {
                    return coin.name.match(re);
                }.bind(this));
            }
        },
        mounted() {
            console.log('Component mounted.');
            const request = function() {
                console.log('Checking coin updates...');
                axios.get( '/api/coins/latest' )
                        .then( function( response ) {
                            var coins = response.data.data;
//                            coins = _.sortBy( coins, [ 'rel'] );
                            _.each( coins, function( coin ) {
                                const origin = _.find( this.coins, { name: coin.name } );
                                if ( origin ) {
                                    coin['up'] = ( coin.value > origin.value || coin.rel > origin.rel );
                                    coin['down'] = ( coin.value < origin.value || coin.rel <  origin.rel );
                                    coin['equal'] = ( !coin.up && !coin.down );
                                }
                                else {
                                    coin['equal'] = true;
                                }

                            }.bind(this));

                            this.coins = response.data.data;
                        }.bind(this));
            }.bind(this);
            request();
            setInterval(function() {
                request();
            }, 5000 );

        }
    }
</script>
