<style>
 <style>
        .central-section {
            max-width: 85%;
            
        }

        .central-section p {
            font-size: 1.1em;
            color: #333;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            color: #333;
        }

        .form-group textarea {
            resize: vertical;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0056b3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>
  <?php include_once("header.php");?>
    <div class="central-section">
        <p>If you need to ask something to the administrators, please leave a message here. You will get a reply as soon as possible.</p>
        <form action="/v1/contactus" method="post" class="internal">
            <div class="form-group">
                <label for="nome">Name</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="your name">
            </div>
            <div class="form-group">
                <label for="messaggio">Message</label>
                <textarea class="form-control" id="messaggio" name="messaggio" rows="10" placeholder="your message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
  <?php include_once("footer.php"); ?>
</body>