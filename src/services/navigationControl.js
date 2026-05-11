import router from "@/router";

const navigationControl = (toRoute) => {
    if (toRoute === '/live') {
        window.location.href = 'https://dtec.tbr.com.br/live/';
        return;
    }

    router.push(toRoute)
}

export default navigationControl;