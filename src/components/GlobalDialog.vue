<script setup>
import { useAlert } from '@/services/alertService';
import { ref, watch } from 'vue';

const { isOpen, options, close } = useAlert();
const dialogRef = ref(null);

const handleCancel = () => {
    if(options.value.isConfirm){
        close(false);
    } else {
        close(true);
    }
};

watch(isOpen, (value) => {
    if(value){
        dialogRef.value.showModal();
    }else{
        dialogRef.value.close();
    }
});
</script>

<template>
    <dialog ref="dialogRef" class="md-dialog" @cancel.prevent="handleCancel">
        <div class="md-content">
            <div class="md-body">
                <h3 class="md-title mb-2">{{ options.title }}</h3>
                <p>{{ options.message }}</p>
            </div>
            <div class="md-actions">
                <button v-if="options.isConfirm" @click="close(false)" class="btn-flat">
                    Cancelar
                </button>
                <button @click="close(true)" class="btn-raised" :class="options.type">
                    {{ options.isConfirm ? 'Confirmar' : 'OK' }}
                </button>
            </div>
        </div>
    </dialog>
</template>

<style scoped>
.md-dialog {
    border: none;
    border-radius: 28px;
    padding: 24px;
    width: 90%;
    max-width: 320px;
    background: #fff;
    box-shadow: 0 24px 38px 3px rgba(0, 0, 0, 0.14);
}

.md-dialog::backdrop {
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(2px);
}

.success {
    color: #2e7d32;
}

.error {
    color: #d32f2f;
}

.warning {
    color: #ed6c02;
}

.info {
    color: #ccc;
}

.md-body h3 {
    margin: 0;
    font-size: 1.3rem;
    color: #1c1b1f;
}

.md-body p {
    color: #49454f;
    line-height: 1.5;
}

.md-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}

.btn-flat {
  background: none;
  border: none;
  padding: 10px 16px;
  color: #1b71b8;
  font-weight: 500;
  cursor: pointer;
}

.btn-raised {
    border: none;
    padding: 10px 24px;
    border-radius: 100px;
    font-weight: 500;
    cursor: pointer;
}

.btn-raised.success {
    background: #2e7d32;
    color: white;
}

.btn-raised.error {
    background: #b3261e;
    color: white;
}

.btn-raised.warning {
    background: #ed6c02;
    color: white;
}

.btn-raised.info {
    background: #ccc;
    color: #1c1b1f;
}
</style>