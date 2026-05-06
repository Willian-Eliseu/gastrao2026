import { readonly, ref } from "vue";

const isOpen = ref(false);
const options = ref({
    title: '',
    message: '',
    type: 'info',
    isConfirm: false
});

let resolvePromise;

export function useAlert(){
    const showAlert = (config) => {
        options.value = {
            ...config,
            type: config.type || 'info',
            isConfirm: config.isConfirm || false
        };

        isOpen.value = true;

        return new Promise((resolve)=>{
            resolvePromise = resolve;
        });
    };

    const close = (response) => {
        isOpen.value = false;
        if(resolvePromise){
            resolvePromise(response);
        }
    }

    return{
        isOpen: readonly(isOpen),
        options: readonly(options),
        showAlert,
        close
    };
}