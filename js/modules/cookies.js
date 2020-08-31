const setCookie = (name, value, expire, secure) =>  {
    if (navigator.cookieEnabled) {
        const cookieName = encodeURIComponent(name);
        const cookieValue = encodeURIComponent(value);
        let cookieText = `${cookieName}=${cookieValue}`;

        if (typeof days === "number") {
            const data = new Date();
            data.setTime(data.getTime() + (days * 24*60*60*1000));
            cookieText += "; expires=" + data.toGMTString();
        }
        if (secure) cookieText += "; secure";

        document.cookie = cookieText;
    }
}

const getCookie = name => {
    if(document.cookie !== ``) {
        const cookies = document.cookie.split(/; */);

        for(let i=0; i<cookies.length; i++) {
            let cookieName = cookies[i].split("=")[0];
            let cookieValue = cookies[i].split("=")[1];
            if (cookieName === decodeURIComponent(name)) return decodeURIComponent(cookieValue);
        };
    }
}

const removeCookie = name => {
    const cookieName = encodeURIComponent(name);
    document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}