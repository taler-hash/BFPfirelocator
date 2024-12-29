<template>
   <img :src="Logo" :class="`h-${setSize()} object-contain`"/>
</template>
<script setup lang="ts">
import Logo from '@/Assets/Logo';

const props = defineProps(['size'])

function setSize(){ 
    if(!props?.size) return 24

    const sizes:{[key: string]: number} = {
        small : 14,
        base: 24
    }

    return sizes[props.size]
}
</script>
