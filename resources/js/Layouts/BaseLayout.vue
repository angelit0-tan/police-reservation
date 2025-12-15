<template>
    <div class="container">
        <component :is="layoutComponent">
            <slot></slot>
        </component>
    </div>
</template>
<script setup>
    import GuestLayout from './GuestLayout.vue';
    const layoutComponent = GuestLayout;
</script>