require('./../bootstrap.js');

let axios = window.axios;
const baseUrl = window.miniSend.baseUrl;
const apiBaseUrl = `${baseUrl}/api`

const sendMessage = (data) => {
    return new Promise((resolve, reject) => {
    	data['userId'] = 1;
        axios.post(`${apiBaseUrl}/messages`, data)
            .then((res) => {
                console.log("Created a new message:", res)
                resolve(res.data.data)
            })
            .catch((err) => reject(err))
    })
}

const fetchMessages = (data) => {
    return new Promise((resolve, reject) => {
        axios.get(`${apiBaseUrl}/messages?userId=1`, {
        	params: data
        })
            .then((res) => {
                console.log("Fetched messages:", res)
                resolve(res.data.data)
            })
            .catch((err) => reject(err))
    })
}

const fetchMessageDetails = (messageId) => {
    return new Promise((resolve, reject) => {
    	if (!messageId) {
    		reject("You need to provide a message id");
    		return;
    	}

        axios.get(`${apiBaseUrl}/messages/${messageId}?userId=1`, )
            .then((res) => {
                console.log("Fetched messages:", res)
                resolve(res.data.data)
            })
            .catch((err) => reject(err))
    })
}


export default {
    sendMessage,
    fetchMessages,
    fetchMessageDetails
}
