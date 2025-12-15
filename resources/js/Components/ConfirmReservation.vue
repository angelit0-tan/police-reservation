<template>
    <div>
        <div>
            <h3>Confirm Your Reservation</h3>
        </div>
        <div>
            
            <div class="row g-3 align-items-center mb-2">
                <div class="col-auto">
                    <label for="pin" class="col-form-label">PIN</label>
                </div>
                <div class="col-auto">
                    <input type="text" id="pin" class="form-control" v-model="pin">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary w-100" @click="confirm">Confirm</button>
                </div>
                <div class="col-auto">
                    <span class="text-danger" v-if="error"><b>Error:</b> {{ error }} </span>
                    <span class="text-primary" v-if="success"><b>Success:</b> {{ success }} </span>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import axios from 'axios';
    import { ref } from 'vue';

    const pin = ref(null);
    const error = ref(null);
    const success = ref(null);
    const emit = defineEmits(['update']);

    const confirm = async() => {
        error.value = null;
        success.value = null;

        await axios.post(`/api/confirm`, {
            pin: pin.value,
        }).then((data) => {
            success.value = data.data.message;
            emit('update');
        }).catch((e) => {
            error.value = e.response.data.message;
        });
    }
</script>