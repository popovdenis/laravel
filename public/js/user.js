var userObject = {
    updateUserFormId: '#update-user-form',
    
    init: function () {
        var self = this;
        
        $('.update-user-btn').on('click', function () {
            self.updateUser();
        });
    },
    
    updateUser: function () {
        var self = this;
        var form = $(self.updateUserFormId);
        if (form.length) {
            form.submit();
        }
    }
};
