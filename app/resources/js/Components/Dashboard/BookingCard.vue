<template>
    <div class="bg-white w-full p-4 border rounded-lg shadow-sm mt-4 ">
        <div class="flex items-center justify-between pb-4">
            <p class="font-black text-2xl">Bookings</p>
            <span class="text-2xl pi pi-send"></span>
        </div>
        <div class="w-full flex justify-center">
            <Chart type="doughnut" :data="chartData" :options="chartOptions" class="w-full md:w-[30rem]" />
        </div>
    </div>
</template>
<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Chart from 'primevue/chart';
import axios from 'axios';

const chartData = ref<any>();
const chartOptions = ref<any>(null);

onMounted(async () => {
    chartData.value = await setChartData();
    chartOptions.value =  setChartOptions();
});

const setChartData = async () => {
    const res = await axios.get(route('bookings.count'))
    let status: any = {
        completed: {
            value: 'completed',
            color: '#16a34a',
            count: 0,
        },
        pending: {
            value: 'pending',
            color: '#ca8a04',
            count: 0,
        },
        ongoing: {
            value: 'ongoing',
            color: '#9333ea',
            count: 0,
        },
        accepted: {
            value: 'accepted',
            color: '#2563eb',
            count: 0,
        },
        cancelled: {
            value: 'cancelled',
            color: '#dc2626',
            count: 0,
        },
    }

    if(res.status === 200) {
        res.data.map((item: any) => {
            status[item.status].count = item.count
        })
    }
    
    const colors = Object.values(status).map((item: any) => ( item.color))
    const counts = Object.values(status).map((item: any) => ( item.count))
    return {
        labels: Object.keys(status),
        datasets: [
            {
                data: counts,
                backgroundColor: colors,
                hoverBackgroundColor: colors
            }
        ]
    };
};

const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');

    return {
        plugins: {
            legend: {
                labels: {
                    cutout: '60%',
                    color: textColor
                }
            }
        }
    };
};
</script>