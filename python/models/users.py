from models.main import main
class users(main):
    def __init__(self):
        super().__init__([
            'id',
            'username',
            'password',
            'title',
            'created_at'
        ],'users')
        


module = users()