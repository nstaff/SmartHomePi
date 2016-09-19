# SmartHomePi
Smart Home Server for Raspberry Pi

This project was for my capstone at Penn State. I worked with a team of 3 other developers to build a framework for integration with vendor provided APIs and try to move towards a less vender centric solution to integrating with multiple devices.
My main contributions were to the server side of this application, the data layer, shell scripts for installing and configuring, as well as receiving and sending the information from the Android App (not here) via the exec/executeCommand.php page. The lib/DataBroker class is likely the best showcase of the style of code that I write within this particular project as it is the most robust object. I wrote the majority of the code for this piece of the project along with another teammate who worked on the UI using the Twitter Bootstrap framework. Test cases are all contained within the TestAll.php file, and other test cases were done using black box methods.
