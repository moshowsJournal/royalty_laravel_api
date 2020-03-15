 
<!DOCTYPE html>
<html>
    <head>
        
        <title>PIN Email</title>
    </head>
    <body>
    
        <div class="container" style="max-width: 1140px;     width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;">
            <p style="margin-top: 0;margin-bottom: 1rem;">Hello {{$data['full_name']}},</p>
            <h5 style="font-size: 1.25rem;">See pin below:</h5>
            <h3 style="margin-bottom: .5rem;font-weight: 500;line-height: 1.2;font-size: 1.75rem;">{{$data['verification_code']}}</h3>
            <p style="margin-top: 0;margin-bottom: 1rem;">If you receive this email by mistake, please simply delete it.</p>
        </div>
    </body>
</html> 