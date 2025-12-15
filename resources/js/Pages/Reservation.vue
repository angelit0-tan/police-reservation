<template>
    <div>
        <div class="my-5">
            <h1>Create New Reservation</h1>
        </div>
        <div>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" v-model="form.firstName">
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" v-model="form.lastName">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" v-model="form.phone">
            </div>
            <div class="mb-3">
                <label for="reservation" class="form-label">Time of Reservation</label>
                <input type="datetime-local" class="form-control" id="reservation" v-model="form.reservation">
            </div>
            <div v-if="form.error">
                <span class="text-danger"> {{ form.error }}</span>
            </div>
            <div v-if="form.success">
                <span class="text-primary"> {{ form.success }}</span>
            </div>
            <div class="mt-5">
                <button class="btn btn-primary me-2" @click="submit">Submit</button>
                <a href="/" class="btn btn-danger" @click="submit">Go Back</a>
            </div>
        </div>
    </div>
</template>
<script setup>
    import axios from 'axios';
    import { onMounted, ref } from 'vue';

    const form = ref({
        lastName: null,
        firstName: null,
        phone: null,
        reservation: null,
        error: null,
        success: null
    });

    const submit = async() => {

        form.value.error = null;
        form.value.success = null;

        await axios.post(`/api/reservations`, {
            last_name: form.value.lastName,
            first_name: form.value.firstName,
            phone: form.value.phone,
            reservation_time: form.value.reservation
        }).then((data) => {

            form.value.success = data.data.message
            
            // clear fields
            form.value.lastName = null;
            form.value.firstName = null;
            form.value.phone = null;
            

        }).catch((e) => {
            form.value.error = e.response.data.message;
        });
    }

    onMounted(() => {
        const now = new Date();
        form.value.reservation = new Date(
            now.getTime() - now.getTimezoneOffset() * 60000
        ).toISOString().slice(0, 16);
    })
</script>