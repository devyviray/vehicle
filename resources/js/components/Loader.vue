<template>
    <div class="outer">
        <div class="loader-div">
            <propagate-loader class="custom-class" color="#2dce89" ></propagate-loader>
        </div>
    </div>
    
</template>

<style>
    .outer{
        height: 100vh;
        width: 100%;
        position: absolute;
        background-color: rgba(255,255,255,0.7);
        z-index: 1100;
        text-align: center;
    }
    .sk-spinner[data-v-39432f99]{
        margin-top: 250px;
    }
    .loader-div{
        height: 10%;
        width: 3%;
        margin: 0 auto;
        margin-top: 25%;
    }
</style>

<script>
import { PropagateLoader } from '@saeris/vue-spinners'
export default {
    components:{
       PropagateLoader
    }
    
}
</script>
