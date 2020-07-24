from models.main import main
class users_keys(main):
    def __init__(self):
        super().__init__([
            'id',
            'user_id',
            'user_key',
            'user_label',
            'end_at',
            'created_at'
        ],'users_keys')
        


module = users_keys()