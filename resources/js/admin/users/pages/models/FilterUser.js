export default {
    pagination: {
        page: 1,
        limit: 10,
    },
    sort: {
        field: 'created_at',
        order: 0
    },
    fields: {
        meeting_type: '',
        name: '',
        email: '',
        phone: '',
        connection_type: '',
        session_date: '',
        category_id: '',
    },
    visibleFields: {
        name: true,
        category: true,
        phone: false,
        email: false,
        connection_type: false,
        meeting_type: true,
        session_date: true,
    }
}
