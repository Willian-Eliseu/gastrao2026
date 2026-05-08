<template>
    <div class="col-12">
        <div class="rounded-3 border mb-3 bg-principal-subtle">
            <p class="p-2 px-3 mb-0 fs-6">
                <slot name="title"></slot>
            </p>
        </div>
    </div>
    <slot></slot>
</template>

<style scoped>
.bg-principal-subtle{
    background-color: #1940edd5;
    color: white;
}
</style>