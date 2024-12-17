<?php
// Turn on error reporting for debugging purposes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the file path
$file_path = 'visits.txt';

// Initialize the count variable
$count = 0;

// Check if the file exists
if (file_exists($file_path)) {
    // Try to read the current count from the file
    $file_contents = file_get_contents($file_path);
    if ($file_contents !== false) {
        $count = (int)$file_contents;
    } else {
        // Handle error reading the file
        // echo "Error reading visits.txt";
    }
} else {
    // Create the file if it doesn't exist
    if (file_put_contents($file_path, $count) === false) {
        // Handle error creating the file
        // echo "Error creating visits.txt";
        exit;
    }
}

// Increment the count
$count++;

// Try to write the new count back to the file
if (file_put_contents($file_path, $count) === false) {
    // Handle error writing to the file
    // echo "Error writing to visits.txt";
} else {
    // Optionally, display the visit count
    // echo "This page has been visited $count times.";
}
?>

<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Feedback Generator</title>
    <style>
        .hidden {
            display: none;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 100;
            display: flex;
            background-image: url('2.jpg'); 
            background-size: cover; 
            background-position: center; 
            height: 100vh; 
            margin: 100; 
            background-attachment: fixed;
        }
        #contenttext p {
            margin: 0 0 10px;
        }
        #content {
            padding: 20px;
        }
        #search-box {
            position: fixed;
            top: 50px;
            right: 50px;
            background: white;
            border-radius: 25px;
            padding: 5px;
            background-color: rgba(0, 0, 0, 0.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        #search-box input {
            margin: 5px;
            border: none;
            font-size: 15px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 25px;
            color: #2596be;
        }

        #search-box button {
            margin: 0px;
            font-size: 15px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 15px;
            color: #2596be;
        }

        #search-box button:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .highlight {
            background-color: yellow;
        }
        .current {
            background-color: orange;
        }
    </style>
    <style>
        #sidebar {
            position: fixed;
            top: 2.5vh;
            left: 10;
            width: 70px;
            background-color: rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            height: 90vh;
            overflow-y: auto;
            border-radius: 25px;
        }
        #content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
        }
        .part {
            margin-bottom: 20px;
        }
        .sidebar-link {
            display: block;
            color: #2596be;
            margin-bottom: 10px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.3);
            padding: 5px;
            width: 60px;
            height: 20px;
        }
        .sidebar-link:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
        .sidebar-link:active { 
            transform: scale(0.7); 
            /* Scaling button to 0.7 to its original size */ 
            box-shadow: 3px 2px 22px 1px rgba(255, 255, 255, 0.1); 
            /* Lowering the shadow */ 
        } 
    </style>
    <style>
        .part {
            position: relative;
            padding: 10px;
            border: 1px solid #2596be;
            border-radius: 25px;
            margin-bottom: 10px;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .part:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
        .copy-button{
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 25px;
            font-size: 12px;
            color: #2596be
        }

        .copy-button:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .bar {
            width: 100%;
            text-decoration: none;
            padding: 1px;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            text-align: center;
            border: 1px solid #ddd;
            color: #2596be;
            border-radius: 10px;
            margin-top: 5px;
        }

        .bar-content {
            display: none;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .bar-content a {
            display: block;
            margin: 5px 0;
            text-decoration: none;
        }

    </style>
    <style>
        .upload-container {
            margin: 20px;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }

        #file-upload {
            display: none;
        }

        button {
            padding: 6px 12px;
            cursor: pointer;
            background-color: #2596be;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .category {
            margin-top: 20px;
        }

        h3 {
            cursor: pointer;
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 4px;
        }

        .files {
            display: none;
            margin-left: 20px;
        }

        .file-link {
            display: block;
            margin-top: 5px;
        }

        .tabs {
            overflow: hidden;
            display: flex;
            border-radius: 10px;
        }

        .tabs button {
            background-color: rgba(255, 255, 255, 0.3);
            font-size: 15px;
            color: #2596be;
            margin-left: 5px;
            margin-right: 5px;
            border: 1px solid #2596be;
            border-radius: 10px;
            outline: none;
            cursor: pointer
            padding: 14px 16px;
            transition: background-color 0.3s;
            flex-grow: 1;
        }

        .tabs button:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .tabs button.active {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .tabcontent {
            display: none;
            padding: 20px;
            border-top: none;
        }

        .tabcontent.show {
            display: block;
        }

        .calculate_text h1{
            color: #2596be;
        }

        .answers_text h1{
            color: #2596be;
        }

        .upload_text h1{
            color: #2596be;
        }

        .feedback_text h1{
            color: #2596be;
        }

    </style>

</head>

<body>
    <div id="contenttext">
    <!--<a href="https://docs.qq.com/sheet/DUWJvd1NxVnFpWWJQ?&_t=1719814029386&u=a2367cabf8134c6493298e7eff0816ef">Reference Excel Sheet</a>-->
    <script>
        function showTOEFLOptions() {
            var examType = document.getElementById("examType").value;
            var toeflOptions = document.getElementById("toeflOptions");
            if (examType === "TOEFL") {
                toeflOptions.classList.remove("hidden");
            } else {
                toeflOptions.classList.add("hidden");
            }
        }

        function showIELTSOptions() {
            var examType = document.getElementById("examType").value;
            var ieltsOptions = document.getElementById("ieltsOptions");
            if (examType === "IELTS") {
                ieltsOptions.classList.remove("hidden");
            } else {
                ieltsOptions.classList.add("hidden");
            }
        }

        function showReadingInput() {
            var sectionType = document.querySelector('input[name="sectionType"]:checked').value;
            var readingInputs = document.getElementById("readingInputs");
            var generateButton_t_r = document.getElementById("generateButton_t_r");

            if (sectionType === "reading") {
                readingInputs.classList.remove("hidden");
            } else {
                readingInputs.classList.add("hidden");
            }
            
            generateButton_t_r.classList.remove("hidden");
        }

        function showiReadingInput() {
            var sectionType = document.querySelector('input[name="sectionType"]:checked').value;
            var readingInputs = document.getElementById("ireadingInputs");
            var generateButton_i_r = document.getElementById("generateButton_i_r");

            if (sectionType === "reading") {
                readingInputs.classList.remove("hidden");
            } else {
                readingInputs.classList.add("hidden");
            }
            
            generateButton_i_r.classList.remove("hidden");
        }

        function showlisteningInput() {
            var sectionType = document.querySelector('input[name="sectionType"]:checked').value;
            var listeningInputs = document.getElementById("listeningInputs");
            var generateButton_t_l = document.getElementById("generateButton_t_l");

            if (sectionType === "listening") {
                listeningInputs.classList.remove("hidden");
            } else {
                listeningInputs.classList.add("hidden");
            }
            
            generateButton_t_l.classList.remove("hidden");
        }

        function showilisteningInput() {
            var sectionType = document.querySelector('input[name="sectionType"]:checked').value;
            var listeningInputs = document.getElementById("ilisteningInputs");
            var generateButton_i_l = document.getElementById("generateButton_i_l");

            if (sectionType === "listening") {
                listeningInputs.classList.remove("hidden");
            } else {
                listeningInputs.classList.add("hidden");
            }
            
            generateButton_i_l.classList.remove("hidden");
        }

        function generateOutput_t_l() {
            var tlt = document.getElementById("tlt").value;
            var c1i = parseInt(document.getElementById("c1i").value) || 0;
            var l1i = parseInt(document.getElementById("l1i").value) || 0;
            var l2i = parseInt(document.getElementById("l2i").value) || 0;
            var c2i = parseInt(document.getElementById("c2i").value) || 0;
            var c3i = parseInt(document.getElementById("c3i").value) || 0;

            var totalScore = 30;
            var deduction = c1i+l1i+l2i+c2i+c3i;

            var finalScore = Math.floor(totalScore - deduction);

            var final = finalScore.toString();

            var result = "完成一套托福听力"+tlt+",得分："+ final +" 具体错误如下：" +
                         "C1" + "错" + c1i + "题," + "L1" + "错" + l1i+ "题," +
                         "L2" + "错" + l2i + "题," + "C2" + "错" + c2i + "题," +
                         "C3" + "错" + c3i + "题"
                         
            
            document.getElementById("result").innerText = result;
        }

        function generateOutput_i_l() {
            var ilt = document.getElementById("ilt").value;
            var p1il = parseInt(document.getElementById("p1il").value) || 0;
            var p2il = parseInt(document.getElementById("p2il").value) || 0;
            var p3il = parseInt(document.getElementById("p3il").value) || 0;
            var p4il = parseInt(document.getElementById("p4il").value) || 0;
            
            var totalScore = 40;
            var deduction = p1il+p2il+p3il+p4il;

            var finalScore = totalScore - deduction;

            var band;

            if(finalScore>=39){
                band = 9.0;
            }
            else if (finalScore>=37 && finalScore<=38){
                band = 8.5;
            }
            else if (finalScore>=35 && finalScore<=36){
                band = 8.0;
            }
            else if (finalScore>=32 && finalScore<=34){
                band = 7.5;
            }
            else if (finalScore>=30 && finalScore<=31){
                band = 7.0;
            }
            else if (finalScore>=26 && finalScore<=29){
                band = 6.5;
            }
            else if (finalScore>=23 && finalScore<=25){
                band = 6.0;
            }
            else if (finalScore>=18 && finalScore<=22){
                band = 5.5;
            }
            else if (finalScore>=16 && finalScore<=17){
                band = 5.0;
            }
            else if (finalScore>=13 && finalScore<=15){
                band = 4.5;
            }
            else if (finalScore>=10 && finalScore<=12){
                band = 4.0;
            }
            else{
                band=0;
            }
            

            var result = "完成一套雅思听力"+ilt+"，得分："+ band +" ,具体错误如下：" +
                         "P1" + " 错" + p1il + "题，" + "P2" + " 错" + p2il+ "题，" +
                         "P3" + " 错" + p3il + "题，" + "P4" + " 错" + p4il + "题。"
                         
            
            document.getElementById("result").innerText = result;
        }

        function generateOutput_t_r() {
            var trt = document.getElementById("trt").value;
            var t_1 = document.getElementById("t_1").value;
            var t_1_s = parseInt(document.getElementById("t_1_s").value) || 0;
            var t_1_m = parseInt(document.getElementById("t_1_m").value) || 0;
            var t_2 = document.getElementById("t_2").value;
            var t_2_s = parseInt(document.getElementById("t_2_s").value) || 0;
            var t_2_m = parseInt(document.getElementById("t_2_m").value) || 0;

            var totalScore = 30;
            var deduction_s = t_1_s * 1.5 + t_2_s * 1.5;
            if (t_1_m > 1){
                var t_1_de = 2;
            }
            else if (t_1_m == 0){
                var t_1_de = 0;
            }
            else{
                var t_1_de = 1;
            }

            if (t_2_m > 1){
                var t_2_de = 2;
            }
            else if (t_2_m == 0){
                var t_2_de = 0;
            }
            else{
                var t_2_de = 1;
            }
            deduction = deduction_s + t_1_de + t_2_de;

            var finalScore = Math.floor(totalScore - deduction);

            var final = finalScore.toString();

            var result = "完成一套托福阅读"+trt+"，得分："+ final +" ,具体错误如下：" +
                         t_1 + " 错 " + t_1_s + "单选，" + t_1_m + "多选，" +
                         t_2 + " 错 " + t_2_s + "单选，" + t_2_m + "多选。"
                         
            
            document.getElementById("result").innerText = result;
        }

        function generateOutput_i_r() {
            var irt = document.getElementById("irt").value;
            var p1ir = parseInt(document.getElementById("p1ir").value) || 0;
            var p2ir = parseInt(document.getElementById("p2ir").value) || 0;
            var p3ir = parseInt(document.getElementById("p3ir").value) || 0;
            
            var totalScore = 40;
            var deduction = p1ir+p2ir+p3ir;

            var finalScore = totalScore - deduction;

            var band;

            if(finalScore>=39){
                band = 9.0;
            }
            else if (finalScore>=37 && finalScore<=38){
                band = 8.5;
            }
            else if (finalScore>=35 && finalScore<=36){
                band = 8.0;
            }
            else if (finalScore>=33 && finalScore<=34){
                band = 7.5;
            }
            else if (finalScore>=30 && finalScore<=32){
                band = 7.0;
            }
            else if (finalScore>=27 && finalScore<=29){
                band = 6.5;
            }
            else if (finalScore>=23 && finalScore<=26){
                band = 6.0;
            }
            else if (finalScore>=19 && finalScore<=22){
                band = 5.5;
            }
            else if (finalScore>=15 && finalScore<=18){
                band = 5.0;
            }
            else if (finalScore>=13 && finalScore<=14){
                band = 4.5;
            }
            else if (finalScore>=10 && finalScore<=12){
                band = 4.0;
            }
            else{
                band=0;
            }

            var result = "完成一套雅思阅读"+irt+"，得分："+ band +" ,具体错误如下：" +
                         "P1" + " 错" + p1ir + "题，" + "P2" + " 错" + p2ir+ "题，" +
                         "P3" + " 错" + p3ir + "题。"
                         
            
            document.getElementById("result").innerText = result;
        }
    </script>
</head>
<body>
    <div class="calculate_text">
    <h1>Calculate Scores</h1>
    </div>
    <label for="examType">Select Exam:</label>
    <select id="examType"  onchange = "showIELTSOptions(), showTOEFLOptions()" style="background-color:rgba(255, 255, 255, 0.7);border:none">
        <option value=></option>
        <option value="IELTS">IELTS</option>
        <option value="TOEFL">TOEFL</option>
    </select>

    <div id="ieltsOptions" class="hidden">
        <input type="radio" id="ireading" name="sectionType" value="reading" onclick="showiReadingInput()">
        <label for="ireading">Reading</label>
        <input type="radio" id="ilistening" name="sectionType" value="listening" onclick="showilisteningInput()">
        <label for="ilistening">Listening</label>

        <div id="ireadingInputs" class="hidden">
            <br><br>
            <label for="irt">Test Title: </label>
            <input type="text" id="irt" style="background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br><br>
            <label>Passage 1:</label>
            <label for="p1ir">Incorrect:</label>
            <input type="number" id="p1ir" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label>Passage 2:</label>
            <label for="p2ir">Incorrect:</label>
            <input type="number" id="p2ir" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label>Passage 3:</label>
            <label for="p3ir">Incorrect:</label>
            <input type="number" id="p3ir" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
        </div>

        <div id="ilisteningInputs" class="hidden">
            <br><br>
            <label for="ilt">Test Title: </label>
            <input type="text" id="ilt" style="background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br><br>
            <label">P1:</label>
            <label for="p1il">Incorrect:</label>
            <input type="number" id="p1il" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label">P2:</label>
            <label for="p2il">Incorrect:</label>
            <input type="number" id="p2il" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label">P3:</label>
            <label for="p3il">Incorrect:</label>
            <input type="number" id="p3il" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label">P4:</label>
            <label for="p4il">Incorrect:</label>
            <input type="number" id="p4il" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
        </div>
    </div>




    <div id="toeflOptions" class="hidden">
        <input type="radio" id="reading" name="sectionType" value="reading" onclick="showReadingInput()">
        <label for="reading">Reading</label>
        <input type="radio" id="listening" name="sectionType" value="listening" onclick="showlisteningInput()">
        <label for="listening">Listening</label>

        <div id="readingInputs" class="hidden">
            <br><br>
            <label for="trt">Test Title: </label>
            <input type="text" id="trt" style="background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br><br>
            <label for="t_1">First Passage:</label>
            <input type="text" id="t_1" style="background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label for="t_1_s">Incorrect Single:</label>
            <input type="number" id="t_1_s" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label for="t_1_m">Incorrect Multiple:</label>
            <input type="number" id="t_1_m" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <br>
            <label for="t_2">Second Passage:</label>
            <input type="text" id="t_2" style="background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label for="t_2_s">Incorrect Single:</label>
            <input type="number" id="t_2_s" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label for="t_2_m">Incorrect Multiple:</label>
            <input type="number" id="t_2_m" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
        </div>

        <div id="listeningInputs" class="hidden">
            <br><br>
            <label for="tlt">Test Title: </label>
            <input type="text" id="tlt" style="background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br><br>
            <label">C1:</label>
            <label for="c1i">Incorrect:</label>
            <input type="number" id="c1i" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label">L1:</label>
            <label for="l1i">Incorrect:</label>
            <input type="number" id="l1i" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label">L2:</label>
            <label for="l2i">Incorrect:</label>
            <input type="number" id="l2i" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label">C2:</label>
            <label for="c2i">Incorrect:</label>
            <input type="number" id="c2i" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
            <label">C3:</label>
            <label for="c3i">Incorrect:</label>
            <input type="number" id="c3i" style="width: 40px;background-color:rgba(255, 255, 255, 0.5);border:none;border-radius:5px"><br>
        </div>
    </div>
    <button id="generateButton_i_r" class="hidden" onclick="generateOutput_i_r()">Calculate</button>
    <button id="generateButton_i_l" class="hidden" onclick="generateOutput_i_l()">Calculate</button>
    <button id="generateButton_t_r" class="hidden" onclick="generateOutput_t_r()">Calculate</button>
    <button id="generateButton_t_l" class="hidden" onclick="generateOutput_t_l()">Calculate</button>
    
    
    <p id="result"></p>
    <br><br>
    <div class="upload_text">
    <h1>Upload Answer Keys</h1>
    </div>
    <form id="myForm">
    <input type="file" id="inpFile"><br>
    <select id="category" style="background-color:rgba(255, 255, 255, 0.7);border:none">
        <option value="XHD">XHD</option>
        <option value="剑雅">剑雅</option>
        <option value="托福">托福</option>
        <option value="extra">extra</option>
    </select><br>
    <progress id="progressBar" value="0" max="100"></progress><br>
    <button type="submit">Upload</button>
</form>


<script>
    const myForm = document.getElementById("myForm");
    const inpFile = document.getElementById("inpFile");
    const category = document.getElementById("category");
    const progressBar = document.getElementById("progressBar");

    const CHUNK_SIZE = 5 * 1024 * 1024; // 5MB chunk size

    myForm.addEventListener("submit", e => {
        e.preventDefault();
        const file = inpFile.files[0];
        const endpoint = "upload.php";
        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        let currentChunk = 0;

        const uploadChunk = () => {
            const start = currentChunk * CHUNK_SIZE;
            const end = Math.min(start + CHUNK_SIZE, file.size);
            const blob = file.slice(start, end);
            const formData = new FormData();
            formData.append("inpFile", blob);
            formData.append("category", category.value);
            formData.append("totalChunks", totalChunks);
            formData.append("currentChunk", currentChunk);
            formData.append("fileName", file.name);

            fetch(endpoint, {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    return response.text(); // or response.json() if your server returns JSON
                } else {
                    throw new Error('File upload failed');
                }
            })
            .then(data => {
                currentChunk++;
                // Update progress bar
                const progress = (currentChunk / totalChunks) * 100;
                progressBar.value = progress;

                if (currentChunk < totalChunks) {
                    uploadChunk();
                } else {
                    alert("File uploaded successfully!");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("File upload failed. Please try again.");
            });
        };

        uploadChunk();
    });
</script>

    <!--
    <div class="bar" onclick="toggleFolder('XHD')">XHD ▼</div>
    <div class="content" id="XHD"></div>
    
    <div class="bar" onclick="toggleFolder('剑雅')">剑雅 ▼</div>
    <div class="content" id="剑雅"></div>
    
    <div class="bar" onclick="toggleFolder('托福')">托福 ▼</div>
    <div class="content" id="托福"></div>
    
    <div class="bar" onclick="toggleFolder('extra')">extra ▼</div>
    <div class="content" id="extra"></div>

    -->
    <br><br>
    <div class="answers_text">
    <h1>View Answer Keys</h1>
    </div>
    <div class="tabs">
        <button class="tablinks" onclick="toggleFolder('XHD')">XHD</button>
        <button class="tablinks" onclick="toggleFolder('剑雅')">剑雅</button>
        <button class="tablinks" onclick="toggleFolder('托福')">托福</button>
        <button class="tablinks" onclick="toggleFolder('extra')">extra</button>
    </div>

    <div id="XHD" class="tabcontent">
        <!-- Content for XHD tab will be loaded here -->
    </div>
    <div id="剑雅" class="tabcontent">
        <!-- Content for Jianya tab will be loaded here -->
    </div>
    <div id="托福" class="tabcontent">
        <!-- Content for Toefl tab will be loaded here -->
    </div>
    <div id="extra" class="tabcontent">
        <!-- Content for Extra tab will be loaded here -->
    </div>
    
    <script src="script.js"></script>

    <br><br><br>

    <div class="feedback_text">
    <h1>Copy Feedbacks</h1>
    </div>
    <?php
    // Read the content from the file
    $file = 'Output.txt';
    $content = file_get_contents($file);

    // Split content into parts based on the delimiter
    $parts = explode('-------------------------------------------------', $content);

    // Output each part with a unique ID
    foreach ($parts as $index => $part) {
        echo '<div id="part-' . $index . '" class="part">';
        echo nl2br(htmlspecialchars($part));
        echo '<button class="copy-button" onclick="copyToClipboard(this)">Copy</button>';
        echo '</div>';
    }
    ?>
</div>
    

    <div id="sidebar"></div>

    <div id="search-box">
    <input type="text" id="search-input" placeholder="Search...">
    <button onclick="search('next')">Next</button>
    <button onclick="search('prev')">Prev</button>
</div>


    <script>
    let currentIndex = -1;
    let matches = [];


    document.getElementById('search-input').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            search('next');
        }
    });

    

    function bindSidebarLinks() {
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.removeEventListener('click', handleSidebarLinkClick); // Remove previous listeners
            link.addEventListener('click', handleSidebarLinkClick); // Add new listeners
        });
    }

    function handleSidebarLinkClick(event) {
        event.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }


    function search(direction) {
        const searchInput = document.getElementById('search-input').value;
        if (searchInput === '') return;

        const contentDiv = document.getElementById('contenttext');
        const regex = new RegExp(searchInput, 'gi');
        const text = contentDiv.innerHTML.replace(/<span class="highlight">|<\/span>/g, '');
        const newText = text.replace(regex, match => `<span class="highlight">${match}</span>`);
        contentDiv.innerHTML = newText;

        matches = document.querySelectorAll('.highlight');
        if (matches.length === 0) return;

        if (direction === 'next') {
            currentIndex = (currentIndex + 1) % matches.length;
        } else if (direction === 'prev') {
            currentIndex = (currentIndex - 1 + matches.length) % matches.length;
        }

        matches[currentIndex].scrollIntoView({ behavior: 'smooth', block: 'start' });

        bindSidebarLinks();
        
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');

        // Loop through each part and create sidebar links
        <?php foreach ($parts as $index => $part) : ?>
            const part<?php echo $index; ?> = document.getElementById('part-<?php echo $index; ?>');
            const firstFiveChars<?php echo $index; ?> = part<?php echo $index; ?>.textContent.substring(0, 5);

            const link<?php echo $index; ?> = document.createElement('a');
            link<?php echo $index; ?>.textContent = firstFiveChars<?php echo $index; ?>;
            link<?php echo $index; ?>.href = '#part-<?php echo $index; ?>';
            link<?php echo $index; ?>.className = 'sidebar-link';

            link<?php echo $index; ?>.addEventListener('click', function(event) {
                
                event.preventDefault();
                part<?php echo $index; ?>.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            sidebar.appendChild(link<?php echo $index; ?>);
        <?php endforeach; ?>
        bindSidebarLinks();
    });


    function copyToClipboard(element) {
        var parent = element.parentNode;
        var div = document.getElementById(parent.id);
        var lines = div.innerText.split('\n');
        
        // Determine the range of lines to copy
        var startLine = 4; // 5th line (0-based index)
        var endLine = lines.length - 3; // 3rd-to-last line
        
        // Extract the desired lines
        var selectedText = lines.slice(startLine, endLine).join('\n');
        
        // Create a temporary textarea element to facilitate copying to clipboard
        var tempTextArea = document.createElement("textarea");
        tempTextArea.value = selectedText;
        document.body.appendChild(tempTextArea);
        
        // Select and copy the text
        tempTextArea.select();
        document.execCommand("copy");
        
        // Remove the temporary textarea and show an alert
        document.body.removeChild(tempTextArea);
        window.getSelection().removeAllRanges(); // clear current selection
        tempAlert("Copied to clipboard", "2000");
        
        
    }

    function tempAlert(msg,duration)
    {
    var el = document.createElement("div");
    el.setAttribute("style","position:fixed;height:20px;width:139px;padding:10;top:50%;background-color: rgba(0, 0, 0, 0.3);border-radius:25px;color:white");
    el.innerHTML = msg;
    setTimeout(function(){
    el.parentNode.removeChild(el);
    },duration);
    document.body.appendChild(el);
    }


    
</script>

</body>
</html>
