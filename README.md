# 🚗 Parking Seva Portal
A smart parking management system that automates vehicle entry, fee calculation, and receipt generation using **real-time OCR** and a **web-based dashboard**.

## 🔥 Features
- 📸 **Real-time OCR-based vehicle number detection**
- 💰 **Automated fee calculation** (`₹10` for two-wheelers, `₹20` for four-wheelers)
- 🧾 **Receipt generation** with entry time and vehicle details
- 🌐 **Web-based dashboard** for easy parking lot management
- 📊 **Database integration** to store vehicle records securely

## 🛠 Tech Stack
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP, Flask (for OCR processing)
- **Database**: MySQL
- **OCR Engine**: Tesseract.js + OpenCV

## 📸 How It Works
1. **User enters vehicle details manually** or **captures the vehicle number using OCR**.
2. The system detects and processes the vehicle number.
3. Parking fees are **calculated automatically**.
4. A **receipt is generated** and stored in the database.

## 🚀 Installation Guide
1. **Clone the repository**:
2. **Setup database**:
   - Import `database.sql` into MySQL.
   - Update database credentials in `db/connection.php`.
3. **Start the server**:
   - Using XAMPP, start **Apache** and **MySQL**.
   - Place project files in `htdocs`.
4. **Run the project**:
   - Open browser and visit: `http://localhost/Parking_seva`

## ⚡ Usage
- Click on **"Capture"** to use OCR.
- Submit vehicle details to generate a **receipt**.
- Check the **dashboard** for stored records.

## 📷 OCR Accuracy Improvement Tips
- Ensure **good lighting** conditions for clearer text detection.
- Keep the camera **steady** while capturing the number.
- Use **higher resolution cameras** for better recognition.

## 🤝 Contributing
Contributions are welcome! Feel free to **fork** the repo, create a branch, and submit a **pull request**. 😊

## 📜 License
This project is **open-source** under the [MIT License](LICENSE).


