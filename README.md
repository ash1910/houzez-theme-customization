# houzez-theme-customization
Houzez-Theme-Customization 
-- Client: Forrent Space
# Houzez Theme Customization


The issue for personalizing greetings has been addressed. Now, there's
another issue with Houzez that I'd like to solve.
As you may know, the theme provides a "Save Search" button. However, the
"Saved Searches" page looks useless. It only recorded (property)type (see
the screenshot attached).



What we want is when users want to save their search results and click
"Save Search" button, their own "Saved Searches" page in their dashboard
should includes the following information:
Location (City, State, Country), Space type, Price(Rent) range, Size
range, Date Saved, Action button



For example, the saved searches page should look like this:
Atlanta, Georgia, United States, Office, $20-$25/sf/year, 700 sf - 1000
sf, September 6, 2024 Action (Button)
Action: View Listings, Edit, Manage Notifications, Delete

# TASK 2
Here are the examples for "Size" and "Rate" (see the two screenshots). The Rate needs to be changed to "Rent or Rate" because some leasing spaces charge a "Fixed Rent" per month so we need to include this option in the filter.

I guess the most important thing to make the advanced search work is to make the "advanced search" corresponding with the data entered in the listing form (screenshot attached). What do you think?

SELECT * FROM TABLE where REPLACE(CAST(amount AS CHAR), ',', '') BETWEEN 50000 AND 60000 AND amount LIKE "%/m%"    

# TASK 3
We need to add another filter for "Lease Term". See the screenshots for Lease term select in the "Create a listing" form. The first screenshot shows upper portion and the second shows the lower portion. There are total eight different selections. This filter will be placed next to "Type". How much and how long this will take you to finish?

minor change on the requirement. Now there are 9 different selections instead of 8 before. We added "Venue contract" in there. See the updated Lease term selections in the form (screenshots attached). Let me know if this change will cause additional fee and how much more. Thanks.
