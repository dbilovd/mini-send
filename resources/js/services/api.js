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

        axios.get(`${apiBaseUrl}/messages/${messageId}?userId=1`)
            .then((res) => {
                console.log("Fetched messages:", res)
                resolve(res.data.data)
            })
            .catch((err) => reject(err))
    })
}

const uploadAttachment = (data) => {
    return new Promise((resolve, reject) => {
    	if (!data) {
    		reject("You need to provide a file to upload.");
    		return;
    	}

        axios.post(`${apiBaseUrl}/attachments?userId=1`, data, {
        	headers: {
        		"Content-type": 'multipart/form-data'
        	}
        })
            .then((res) => {
                console.log("Uploaded attachment:", res)
                resolve(res.data.data)
            })
            .catch((err) => reject(err))
    })
}

const fetchStats = () => {
    return new Promise((resolve, reject) => {
        axios.get(`${apiBaseUrl}/stats?userId=1`)
            .then((res) => {
                console.log("Fetched stats:", res)
                resolve(res.data.data)
            })
            .catch((err) => reject(err))
    })
}


export default {
    sendMessage,
    fetchMessages,
    fetchMessageDetails,
    uploadAttachment,
    fetchStats
}
