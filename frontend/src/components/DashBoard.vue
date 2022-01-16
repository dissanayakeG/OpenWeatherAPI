<template>
    <div class="dashboard">
        <div class="weather">
            <div class="time-details">
                <h1>{{getDate()[0]}}</h1>
                <h4>{{getDate()[1]}}</h4>
                <h2>{{("COLOMBO")}}</h2>
                <h4>{{("Sri Lanka")}}</h4>
            </div>
            <div class="weather-details" v-if="!loading">
                <h4 class="display-3">{{currentWeatherDetails.temperature}}
                    <span class="symbol">°</span>C
                </h4>
                <h5> {{currentWeatherDetails.description}} </h5>

                <div>
                    <h5 class="display-5">
                        {{getRoundedValue(currentWeatherDetails.one_hour_ave_temperature)}}
                        <span class="symbol">°</span>C
                        {{("Last Hour average temperature")}}
                    </h5>
                </div>

                <div>
                    <h5 class="display-5">
                        {{currentWeatherDetails.humidity}}
                        <span>{{("Humidity")}}</span>
                    </h5>
                </div>

                <div>
                    <h5 class="display-5">
                        {{getRoundedValue(currentWeatherDetails.one_hour_ave_humidity)}}
                        <span>{{("Last Hour average Humidity")}}</span>
                    </h5>

                </div>
            </div>

            <div class="weather-details" v-if="loading"><h5>{{("Loading...")}}</h5></div>

            <div class="weather-setting">
                <div>
                    <label for="sqlFrequency">Database update</label>
                    <select class="form-control"
                            id="sqlFrequency"
                            v-model="sqlFrequency"
                            @change="updateSQLDB(sqlFrequency)"
                    >
                        <option disabled value="">Please select one</option>
                        <option v-for="frequency in sqlUpdateFrequency"
                                :value="frequency.value"
                                :key="frequency.value">
                            {{frequency.text}}
                        </option>
                    </select>
                </div>

                <div>
                    <label for="firebaseFrequency">Firebase update</label>
                    <select class="form-control"
                            id="firebaseFrequency"
                            v-model="firebaseFrequency"
                            @change="updateFirebase(sqlFrequency)"
                    >
                        <option disabled value="">Please select one</option>
                        <option v-for="frequency in firebaseUpdateFrequency"
                                :value="frequency.value"
                                :key="frequency.value">
                            {{frequency.text}}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'DashBoard',

        data() {
            return {
                loading: true,
                currentWeatherDetails: {},
                sqlFrequency: 60000,
                sqlUpdateFrequency: [
                    {
                        value: 60000,
                        text: 'every minute'
                    },
                    {
                        value: 5000,
                        text: 'every 5 seconds'
                    },
                ],

                firebaseFrequency: 10000,
                firebaseUpdateFrequency: [
                    {
                        value: 10000,
                        text: 'every 10 seconds'
                    },
                    {
                        value: 5000,
                        text: 'every 5 seconds'
                    },
                ]
            }
        },

        watch:{
            loading(loading){
                this.loading = loading;
            }
        },

        mounted() {
            this.getRealTimeWeather()
            this.updateSQLDB()
            this.updateFirebase()
        },

        methods: {
            getDate() {
                let now = new Date();
                let year = now.getFullYear();
                let month = now.getMonth() + 1;
                let day = now.getDate();
                if (month.toString().length == 1) {
                    month = '0' + month;
                }
                if (day.toString().length == 1) {
                    day = '0' + day;
                }
                let weekday = new Date(year + '/' + month + '/' + day).toLocaleString('en-us', {weekday: 'long'});
                return [weekday, year + '/' + month + '/' + day];
            },

            getRoundedValue(value) {
                return Math.round(value);
            },

            /*
            * fetch realtime weather
            * */
            getRealTimeWeather() {
                setInterval(() => {
                    this.axios.get('/realtime-weather').then(response => {
                        this.currentWeatherDetails = response.data;
                        this.loading = false
                    });
                }, 10000)
            },

            /*
            * update DB
            * */
            updateSQLDB(frequency = 60000) {
                console.log(frequency)
                setInterval(() => {
                    this.axios.get('update-weather-details-in-sql')
                }, frequency)
            },

            /*
            * update firebase
            * */
            updateFirebase(frequency = 10000) {
                console.log(frequency)
                setInterval(() => {
                    this.axios.get('update-weather-details-in-firebase')
                }, frequency)
            }
        }
    }
</script>

<style scoped>
    .dashboard {
        background-color: rgb(104, 189, 203);
        min-height: 100vh;
        width: 100%;
        display: flex;
        background-image: url("../assets/weather.svg");
        /*background-image: url("../assets/weather-1.jpg");*/
        /*background-image: url("../assets/weather-2.jpg");*/
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 100% 100%;
    }

    .weather {
        margin: auto;
        width: 90%;
        height: 90%;
        padding: 10px;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .time-details {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        color: #ebf1f1;
        font-family: 'Nunito', sans-serif;
    }

    .weather-details {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: auto;
        color: #f8f3f3;
    }

    .weather-setting {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        width: 90%;
        position: absolute;
        bottom: 10px;
        color: #e0dcdc;
    }

</style>
