from flask import Flask, request, jsonify
from flask_cors import CORS
import mysql.connector

app = Flask(__name__)
CORS(app)

db = mysql.connector.connect(
    host="localhost",
    user="ktgg1868",
    password="yourpassword",
    database="passwordDB"
)

@app.route('/save-password', methods=['POST'])
def save_password():
    data = request.get_json()
    print("Received data:", data)  # 디버깅 로그 추가
    if not data:
        return jsonify({"success": False, "message": "No data received"}), 400
    
    required_fields = ['keyword', 'password', 'hashedPassword']
    for field in required_fields:
        if field not in data:
            return jsonify({"success": False, "message": f"Missing field: {field}"}), 400
    
    keyword = data['keyword']
    password = data['password']
    hashpassword = data['hashedPassword']  # 여기서 키 이름을 수정
    
    cursor = db.cursor()
    query = "INSERT INTO passwords (keyword, password, hashpassword) VALUES (%s, %s, %s)"
    cursor.execute(query, (keyword, password, hashpassword))
    db.commit()
    
    return jsonify({"success": True, "message": "Password saved successfully"}), 201

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
