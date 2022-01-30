import Client from "../../clients/models/Client";

export default {
    id: '',
    user_name: '',
    status: '',
    comment: '',
    session_date: '',
    session_time: '',
    client: JSON.parse(JSON.stringify(Client))
}
