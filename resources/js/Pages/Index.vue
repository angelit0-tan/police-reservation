<template>
    <div>
        <div class="my-5">
            <confirm-reservation @update="loadData"/>
        </div>
        <hr/>
        <div class="mb-5">
            <div>
                <h3> Reservation lists</h3>
            </div>
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Phone</th>
                            <th scope="col">PIN</th>
                            <th scope="col">Is Confirmed</th>
                            <th scope="col">Valid From</th>
                            <th scope="col">Valid Until</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="reservation in reservations" :key="reservation.id">
                            <th scope="row"> {{ reservation.id }} </th>
                            <td>{{ reservation.first_name }}</td>
                            <td>{{ reservation.last_name }}</td>
                            <td>{{ reservation.phone }}</td>
                            <td>{{ reservation.pin }}</td>
                            <td>{{ reservation.is_confirmed }}</td>
                            <td>{{ reservation.valid_from }}</td>
                            <td>{{ reservation.valid_until }}</td>
                            <td>{{ reservation.created_at }}</td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <pagination 
                        v-model:current-page="currentPage"
                        :total="totalRecords"/>
                </div>
            </div>
        </div>
        <div>
            <a href="/reservation" class="btn btn-primary">Create New Reservation</a>
        </div>
    </div>
</template>
<script setup>
    import ConfirmReservation from '@js/Components/ConfirmReservation.vue';
    import Pagination from '@js/Components/Pagination.vue';
    import { ref, watch } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        reservations: {
            type: Object,
            default: () => {}
        }
    });

    const currentPage = ref(1);
    const reservations = ref(props.reservations.data);
    const totalRecords = ref(props.reservations.meta.total);

    const loadData = async() => {
        const { data } = await axios.get('/api/reservations', { params: {page: currentPage.value} });
        console.log(data)
        reservations.value = data.data;
        totalRecords.value = data.meta.total;
    }

    watch([currentPage], () => {
        loadData();
    });

</script>