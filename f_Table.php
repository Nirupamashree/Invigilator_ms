<!DOCTYPE html>
<html>
<head>
    <title>Faculty Timetable</title>
   <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        h1 {
            color: #181818;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }

        button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 3px;
            font-size: 14px;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #333;
            color: #fff;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        #timetable {
            text-align: center;
        }

        .alert {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>FACULTY TIMETABLE</h1>

    <form>
        <label for="faculty">Enter Faculty Name:</label>
        <input type="text" id="faculty" placeholder="Enter faculty name" />
        <button type="button" onclick="showTimetable()">Show Timetable</button>
    </form>
    <div id="alertMessage" class="alert"></div>
    <div id="timetable"></div>
    <script>
        // JavaScript code here
        var timetables = {
            // Timetable data here
		 "faculty1": [
                ["", "Slot 1", "Slot 2", "Slot 3", "Slot 4", "Slot 5", "Slot 6", "Slot 7", "Slot 8"],
                ["Monday", "", "19CSE311", "", "19CSE332", "", "19CSE313", "19CSE313", ""],
                ["Tuesday", "", "", "19CSE311", "", "", "", "", ""],
                ["Wednesday", "19CSE311", "", "", "19CSE102", "19CSE102", "", "19CSE332", ""],
                ["Thursday", "", "", "18CSE383", "18CSE383", "", "19CSE213", "19CSE213", ""],
                ["Friday", "", "", "19CSE332", "", "", "", "", ""],
            ],

            "faculty2": [
                ["", "Slot 1", "Slot 2", "Slot 3", "Slot 4", "Slot 5", "Slot 6", "Slot 7", "Slot 8"],
                ["Monday", "", "19CSE302", "", "", "", "", "", ""],
                ["Tuesday", "", "19CSE302", "", "19CSE303", "", "", "", "19CSE201"],
                ["Wednesday", "", "19CSE303", "19CSE303", "", "", "", "", "19CSE302"],
                ["Thursday", "", "", "", "19CSE302", "19CSE302", "", "", ""],
                ["Friday", "19CSE302", "", "", "", "", "", "", ""],
            ],
            
            "faculty3": [
                ["", "Slot 1", "Slot 2", "Slot 3", "Slot 4", "Slot 5", "Slot 6", "Slot 7", "Slot 8"],
                ["Monday", "", "", "19CSE313", "", "", "19CSE313", "19CSE313", ""],
                ["Tuesday", "", "19CSE313", "", "", "", "", "", ""],
                ["Wednesday", "19CSE313", "", "19CSE313", "", "", "", "", ""],
                ["Thursday", "19CSE313", "19CSE313", "", "", "", "", "19CSE456", "19CSE456"],
                ["Friday", "", "Mentoring", "", "", "", "", "", ""],
            ],

        };

        function showTimetable() {
            var facultyInput = document.getElementById("faculty").value;
            var faculty = facultyInput.toLowerCase().trim(); // Convert input to lowercase and remove leading/trailing spaces
            var timetable = timetables[faculty];

            var alertMessage = document.getElementById("alertMessage");
            var timetableDiv = document.getElementById("timetable");

            if (timetable) {
                alertMessage.innerHTML = "";
                var tableHtml = "<table><tr>";
                for (var i = 0; i < timetable[0].length; i++) {
                    tableHtml += "<th>" + timetable[0][i] + "</th>";
                }
                tableHtml += "</tr>";

                for (var i = 1; i < timetable.length; i++) {
                    tableHtml += "<tr>";
                    for (var j = 0; j < timetable[i].length; j++) {
                        tableHtml += "<td>" + timetable[i][j] + "</td>";
                    }
                    tableHtml += "</tr>";
                }

                tableHtml += "</table>";

                timetableDiv.innerHTML = tableHtml;
            } else {
                alertMessage.innerHTML = "Faculty timetable not found. Please enter a valid faculty name.";
                timetableDiv.innerHTML = "";
            }
        }
    </script>
</body>
</html>
