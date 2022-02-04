<head>
	<meta name="description" content="CL des xxx &ndash;&nbsp;formulaire de contact">
</head>
    
<section class="container">
    <h1>Contact us</h1>
    
    <!-- Paragraph to display as long as the form is not submitted -->
    <?php if(!$_POST) : ?>
        <p class="mandatory">All the fields are mandatory.</p>
    <?php endif; ?>
    
    <!-- Displaying error messages, if the form was submitted without all the requirements -->
    <?php if(empty($messages["success"])) : ?>
        <?php if(!empty($messages["errors"])) { ?>
            <ul class="error">
                <?php foreach($messages["errors"] as $error) : ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>    
            </ul>
        <?php } ?>

    <!-- Contact form -->
    <form action="index.php?p=contact" method="post">
        <div>
            <label for="lastName">Your last name:</label>
            <input type="text" name="lastName" id="lastName">
        </div>

        <div>
            <label for="firstName">Your first name:</label>
            <input type="text" name="firstName" id="firstName">
        </div>
        
        <div>
            <label for="email">Your email:</label>
            <input type="text" name="email" id="email">
        </div>
        
        <div>
            <label for="message">Your message:</label>
            <textarea id="message" name="message" rows="8" cols="40" maxlength="200"></textarea>
        </div>

        <div>
            <input type="submit" name="sendMessage" id="sendMessage" value="Send message">
        </div>
    </form>
    
    <!-- Displaying the success message, if the form was submitted with all the requirements -->
    <?php else : ?>
        <p class="success"><?= $messages["success"][0] ?></p>
    <?php endif; ?>
    
    <p class="redirect">Back to the <a href="index.php?p=home">Homepage</a></p>
</section>