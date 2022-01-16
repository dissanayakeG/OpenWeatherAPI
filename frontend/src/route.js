import Vue from 'vue';
import VueRouter from 'vue-router';
import DashBoard from "./components/DashBoard";

Vue.use(VueRouter);

export default new VueRouter({
    routes :[
        {
            name: 'dashboard',
            path:'/' ,
            component : DashBoard
        },
        {
            name: 'dashboard',
            path:'/dashboard' ,
            component : DashBoard
        }
    ],

    mode:'history'
});
