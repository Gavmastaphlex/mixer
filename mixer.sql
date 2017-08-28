-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 15, 2013 at 12:02 AM
-- Server version: 5.1.68-cll
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gavinmcg_mixer`
--

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  `ingredientID` int(11) NOT NULL AUTO_INCREMENT,
  `ingredientName` varchar(255) NOT NULL,
  `basicIngredient` tinyint(1) NOT NULL,
  PRIMARY KEY (`ingredientID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=150 ;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredientID`, `ingredientName`, `basicIngredient`) VALUES
(1, 'Egg', 1),
(2, 'Milk', 1),
(3, 'Butter', 1),
(4, 'Salt', 1),
(5, 'Sugar', 1),
(6, 'Cheese', 1),
(121, 'Betroot', 0),
(8, 'Pasta', 0),
(120, 'Feta Cheese', 0),
(119, 'Bread', 0),
(11, 'Tomato', 0),
(118, 'Salmon', 0),
(13, 'Flour', 1),
(14, 'Baking Powder', 1),
(15, 'Baking Soda', 1),
(16, 'Mushroom', 0),
(117, 'Lime', 0),
(18, 'Breadcrumbs', 0),
(19, 'Mayonnaise', 0),
(116, 'Wine', 0),
(115, 'White Wine', 0),
(38, 'Zucchini', 0),
(114, 'Fish Fillet', 0),
(25, 'Carrot', 0),
(113, 'Ground Ginger', 0),
(112, 'Lemon Juice', 0),
(111, 'Condensed Milk', 0),
(110, 'Self Raising Flour', 0),
(109, 'Rice', 0),
(108, 'Tomato Paste', 0),
(37, 'Pasta Sauce', 0),
(39, 'Parsnip', 0),
(107, 'Puff Pastry', 0),
(106, 'Parsley', 0),
(42, 'Banana', 0),
(43, 'Avocado', 0),
(44, 'Red Onion', 0),
(45, 'Coriander', 0),
(46, 'Red Pepper', 0),
(47, 'Pineapple', 0),
(48, 'Spaghetti', 0),
(105, 'Ravioli', 0),
(104, 'Parmesan Cheese', 0),
(51, 'Chicken Thigh', 0),
(52, 'Chinese Five Spice', 0),
(53, 'Pepper', 1),
(103, 'Natural Yoghurt', 0),
(102, 'Chicken Breast', 0),
(101, 'Cheddar Cheese', 0),
(100, 'Salsa', 0),
(90, 'Bacon', 0),
(88, '2 Min Chicken Noodles', 0),
(89, 'Shallot', 0),
(99, 'Chicken Meat', 0),
(98, 'Tortilla', 0),
(97, 'Pawpaw', 0),
(96, 'Strawberry', 0),
(95, 'Orange', 0),
(94, 'Cinnamon', 0),
(93, 'Brown Sugar', 0),
(92, 'Sesame Seeds', 0),
(91, 'Sausage', 0),
(81, 'White Chocolate', 0),
(82, 'Crunchy Peanut Butter', 0),
(83, 'Egg Fried Noodles', 0),
(84, 'Honey', 0),
(85, 'Cornflakes', 0),
(122, 'Salad Leaves', 0),
(123, 'Balsamic Vinegar', 0),
(124, 'Beans', 0),
(125, 'Nutmeg', 0),
(126, 'Chickpeas', 0),
(127, 'Garlic', 0),
(128, 'Tahini', 0),
(129, 'Mandarin', 0),
(131, 'Watermelon', 0),
(132, 'Kiwifruit', 0),
(133, 'Sesame Oil', 0),
(134, 'Cream Cheese', 0),
(135, 'Baked Beans', 0),
(136, 'Corn Chips', 0),
(137, 'Whole Fish', 0),
(138, 'Watercress', 0),
(139, 'Horopito', 0),
(140, 'Kawakawa', 0),
(141, 'Whole Piko Piko', 0),
(142, 'Beer', 0),
(143, 'Lemon', 0),
(144, 'chili', 0),
(145, 'Chili Beans', 0),
(146, 'Beef', 0),
(147, 'Onion', 0),
(148, 'Water', 0),
(149, 'Pork', 0);

-- --------------------------------------------------------

--
-- Table structure for table `measurement`
--

CREATE TABLE IF NOT EXISTS `measurement` (
  `measurementID` int(11) NOT NULL AUTO_INCREMENT,
  `measurement` varchar(30) NOT NULL,
  PRIMARY KEY (`measurementID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `measurement`
--

INSERT INTO `measurement` (`measurementID`, `measurement`) VALUES
(1, 'Cup'),
(2, 'ml'),
(3, 'Tsp'),
(4, 'Tbsp'),
(5, 'grams'),
(6, 'pinch'),
(7, 'Can'),
(8, 'Packet'),
(10, 'Rasher'),
(11, 'Jar'),
(12, 'Litre'),
(13, 'Slice'),
(14, 'Clove'),
(15, 'kg');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `pageID` int(11) NOT NULL AUTO_INCREMENT,
  `pageName` varchar(100) NOT NULL,
  `pageTitle` varchar(100) NOT NULL,
  `pageHeading` varchar(100) NOT NULL,
  `pageDescription` varchar(255) NOT NULL,
  `pageKeywords` varchar(255) NOT NULL,
  `pageContent` text NOT NULL,
  PRIMARY KEY (`pageID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pageID`, `pageName`, `pageTitle`, `pageHeading`, `pageDescription`, `pageKeywords`, `pageContent`) VALUES
(1, 'home', 'Home || Mixer', 'Welcome to Mixer', 'Mixer is a dynamic recipe generator, displaying recipes based on the ingredients inputted by the user.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(2, 'recipes', 'Recipes || Mixer', 'All Recipes', 'All recipes that have been uploaded to the Mixer Website.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(3, 'contact', 'Contact Us || Mixer', 'Contact Us', 'Contact the Mixer Team with any queries, suggestions or critique.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(4, 'about', 'About Us || Mixer', 'About Us', 'About the Mixer Corporation.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(5, 'addRecipe', 'Add Recipe || Mixer', 'Add Recipe', 'Upload a new recipe.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(19, 'userPanel', 'User Panel || Mixer', 'User Panel', 'The User Panel for either users who have logged in.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(6, 'register', 'Register || Mixer', 'Register', 'Register with Mixer.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(7, 'recipeResults', 'Recipe Results || Mixer', 'Recipe Results', 'Recipe results based on ingredients inputted.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(8, 'searchResults', 'Search Results || Mixer', 'Search Results', 'Results from searching the whole site for a keyword.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(9, 'userList', 'User List || Mixer', 'User List', 'ADMIN ONLY - The list of users who are signed up to Mixer.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(10, 'sitemap', 'Sitemap || Mixer', 'Sitemap', 'The Sitemap of the Mixer site.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(11, 'userSummary', 'User Summary || Mixer', 'User Summary', 'ADMIN ONLY - The summary of a selected user.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(22, 'editRecipe', 'Edit Recipe || Mixer', 'Edit Recipe', 'Edit an existing recipe.', 'Mixer, cooking, flatting, student, recipes, ingredients etc', ''),
(21, 'recipe', '', '', '', 'Mixer, cooking, flatting, student, recipes, ingredients etc', '');

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE IF NOT EXISTS `recipe` (
  `recipeID` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `recipeName` varchar(255) NOT NULL,
  `recipeImage` varchar(60) NOT NULL,
  `recipeDescription` text NOT NULL,
  `recipeMethod` text NOT NULL,
  `recipeUploadDate` varchar(20) NOT NULL,
  PRIMARY KEY (`recipeID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`recipeID`, `userId`, `recipeName`, `recipeImage`, `recipeDescription`, `recipeMethod`, `recipeUploadDate`) VALUES
(17, 1, 'Veggie Ribbon Pasta', 'veggieRibbons.jpg', 'Healthy and Delicious!', 'Wash the veggies, peel the carrot & parsnip. Take a veggie peeler and make ribbons out of each. Bring saucepan of salted water to the boil. Add the veggie ribbons and boil for 3 minutes. Remove and drain. Pop into a large nonstick frying pan and season. Pour in Pasta sauce and simmer until warmed thru. Serve immediately.\r\n\r\nOptional: Sprinkle with Parmesan cheese and garnish with fresh basil.', '2012-12-12'),
(18, 1, 'Banana Bread', 'bananaBread.jpg', 'For those days when your bunch of bananas dwindles to two really overripe ones â€¦ donâ€™t throw them out, they are PERFECT for this recipe!', 'Preheat oven 180C. Line a loaf tin with baking paper. In a medium bowl, add bananas and sugar and stir. Add mayonnaise, sifted flour and a pinch of salt then lightly mix, until combined. Pour into tin and cook for 50-60 minutes or until a skewer, inserted into the middle, comes out clean. Allow to cool for 10 minutes, then turn onto wire rack. Enjoy it warm straight from the oven or lightly grill it and then enjoy with butter melting on top.\r\n\r\n \r\n\r\nOptional Add 2 tbs. of golden syrup when mixing bananas and sugar.', '2012-12-12'),
(19, 1, 'Avocado Salsa', 'avocadoSalsa.jpg', 'Yummy avocado salsa!', 'Mash avocado (reserving seed), add remaining ingredients and mix well. Place the seed back into the dip to help prevent discolouration and refrigerate until needed. \r\n\r\nOptional: Season with sea salt and pepper.', '2012-12-12'),
(20, 8, 'Veggie Kebabs', 'vegeKebabs.jpg', 'The word kebab is Arabic and means on a skewer!', 'Cut the red peppers, red onion & pineapple into chunks and thread alternately with mushrooms onto a soaked skewer. Season then grill on a hot BBQ plate for 1 to 2 minutes each side or until tender.\r\n\r\n \r\n\r\nOptional: Serve with a delicious Sweet Chilli Sauce to dip.', '2012-12-12'),
(21, 8, 'Spaghetti Cupcakes', 'spaghettiCupcakes.jpg', 'Yummy spaghetti cupcakes!', 'Preheat oven to 180C. In a bowl beat eggs, then add 1 and a half cups cheese and the spaghetti and stir to combine. Grease muffin tins and evenly distribute the mixture across 12 muffin cups. Top each with remaining cheese and bake for 15 minutes.\r\n\r\n\r\nOptional: Have your kids decorate the tops of their cupcakes with sliced olives, mushrooms, ham or capsicum.', '2012-12-12'),
(23, 10, 'Jacks Fried Chicken', 'JFC.jpg', 'Just like Kentucky Fried Chicken, but Jacks!', 'Mix breadcrumbs with spices, salt and pepper. Cut chicken into strips and coat with breadcrumb and spice mix. Place on baking tray and bake in a hot oven (200 F) for 25 mins, turning half-way through. For added goldeness, spray olive oil on strips before putting into oven. Serve with mayonnaise and ketchup.', '2012-12-12'),
(37, 1, 'White Chocolate Spiders', 'WhiteChocolateSpiders.jpg', 'Never makes enough!', 'In a large, clean and dry bowl, melt the chocolate and peanut butter in a microwave on 30 second increments, stirring until combined. Add noodles and stir to throughly coat. Using a tablespoon, dollop onto a paper lined baking tray and chill ... Enjoy!', '2013-03-01'),
(38, 1, 'Honey Joys', 'HoneyJoys.jpg', 'An absolute favourite in all kidâ€™s (and parents) lunchboxes!', 'Preheat oven 150C / 300F.  Heat butter, sugar and honey in small saucepan till frothy then remove from heat.  Add cornflakes and mix well.  Spoon into patty cake / cupcake cases and bake for 10 minutes. Remove, and cool to set.', '2013-03-01'),
(39, 1, 'Savoury Fritters', 'LD512f452be8f4aSavoury-Fritters.jpeg', 'Makes 10', 'Cook noodles (without flavouring) then drain. Combine noodles with flavour sachet, eggs, shallots and bacon. Place egg rings in a non-stick fry pan and spoon enough mixture to fill the ring, cook on both sides until golden.', '2013-03-01'),
(40, 1, 'Sesame Sausages', 'Sesame-Sausages.jpeg', 'Watch these fly!', 'Preheat oven to 200Â°C / 400Â°F. Scatter sausages over a baking paper lined tray. Cook for 15 minutes, drain. Drizzle with honey and cook for another 15 minutes, turning a couple of times until the sausages are sticky and golden all over. Sprinkle with sesame seeds and cook for a final 5 minutes.', '2013-03-01'),
(41, 1, 'Peanut Butter Cookies', 'Peanut-Butter-Cookies-150x150.jpg', 'Gluten-free goodness!', 'Preheat oven to 180C.  Mix all ingredients into a bowl. Spoon small tablespoon sized balls onto 2 lined baking trays.  Slightly flatten with a fork, crisscross style.  Bake for 8 minutes, or until a thin crust forms on the cookie.\r\n\r\nOptional: These work just as well without cinnamon, store well and â€œthe kids love them!â€', '2013-03-01'),
(42, 1, 'Flu Buster Smoothie', 'Flu-Buster-Smoothie.jpg', 'This terrific smoothie has a triple dose of vitamin C and perfect for those â€˜feeling-lousyâ€™ kind of days.', 'Place all ingredients in blender and blend until smooth.', '2013-03-01'),
(43, 1, 'Chicken Carnival Cones', 'Chicken-Carnival-Cones.jpg', 'A sensational recipe to use every ounce of chicken meat from a roast chicken carcass.', 'Fold tortilla in half (if small, fold only the bottom third up) and roll into a cone. Fill bottom of cone with chicken, dollop 2 tsp. Salsa before covering with a layer of cheese. Place on a paper lined baking tray and repeat the process until all ingredients are used. Bake in a 180C oven for 15 minutes.', '2013-03-01'),
(44, 1, 'Sweet Chicken Fingers', 'Sweet-chicken-fingers1.jpg', 'A new sensation!', 'Preheat oven to 180C / 375F. Combine cornflakes and cheese together in one bowl, and place yoghurt in another bowl. Coat chicken with yoghurt and then cornflake and cheese mixture. Bake in oven for 20 â€“ 30 minutes, depending on size of chicken breasts.', '2013-03-01'),
(45, 1, 'Baked Ravioli', 'Baked-Ravioli.jpg', 'Serves 4 â€“ 6', 'Preheat oven to 190C / 380F. Cook ravioli in boiling water until it is just cooked, drain. Line a casserole dish with a thin layer of pasta sauce, add a layer of ravioli and sprinkle a layer of cheese and a touch of parsley. Repeat layering process finishing with a layer of ravioli, topped with remaining pasta sauce, and a sprinkling of the last cup of cheese. Bake for 15 minutes or until cheese is melted and bubbly. Cut into squares as you would lasagne.\r\n\r\nOptional: Serve hot with salad, homemade potato chips or just by itself.', '2013-03-01'),
(46, 1, 'Savoury Scrolls', 'Savoury-Scrolls.jpg', 'Scrumptious Savoury Scrolls!', 'Smooth tomato paste over the sheet of pastry. Scatter chopped bacon and cheese and roll into a log. Cut into pinwheels (2cm thick) and bake in a preheated 180C oven for 15 minutes or until pastry turns a golden brown.', '2013-03-01'),
(47, 1, 'Chicken Nuggets', '4I-Too-Easy-Chicken-Nuggets.jpg', 'These are just too easy to make!', 'Preheat oven to 180C. Cut the Chicken Breasts into bite sized pieces and coat with mayonnaise and roll in breadcrumbs.\r\n\r\nLay on a baking paper lined baking tray. Drizzle with a little butter and bake for 20 minutes.', '2013-03-01'),
(48, 1, 'Creamy Rice', 'Creamy-Rice-LR.jpg', 'A very creamy rice pudding.', 'In a large saucepan bring all the ingredients to the boil. Reduce heat and simmer until the rice has absorbed all of the milk. This will take approximately 1â€“1Â½ hours so stir continuously to avoid the rice sticking to the bottom of the pan and burning.', '2013-03-01'),
(49, 1, 'Clever Cupcakes', 'LD512fd8b62cfeeClever-Cupcakes1.jpg', 'Scrumptious Cupcakes.', 'Preheat oven 180C/360F. Sift flour into a bowl and add remaining ingredients, beat with an electric mixer for two minutes until pale and fluffy. Line a patty cake tin with patty papers. Even scoop mixture across each and bake for 12 minutes until light golden brown. Optional: For more of a vanilla taste add a teaspoon of vanilla essence to the batter.', '2013-03-01'),
(50, 1, 'Gingered Carrots', 'LD512fdb9649e29GingeredCarrots copy.jpg', 'Gingered Carrots.', 'Place carrots in a baking dish. Mix lemon juice and ginger and season with sea salt and pepper, pour over carrots then dot with butter. Cover and bake in a preheated 180C / 360F oven for 30 to 40 minutes or until tender.', '2013-03-01'),
(52, 1, 'Grilled Fish with Buttery Orange Sauce', 'LD512fecc3745a5FishwithOrangeSauce.jpg', '', 'Place 1 tbsp. butter in heavy frying pan/skillet and heat.  Place fish in pan.  Blend 2 tbsp. orange juice and wine with remaining 2 tbsp. melted butter, pour half over the fish fillets.  Sprinkle with sea salt, pepper and 2 tbsp. orange zest.  Cook for 2 minutes, turn, then pour remaining sauce over the fish and continue cooking until done.', '2013-03-01'),
(53, 1, 'Lime & Salmon Cakes', 'LimeSalmonCakes.jpg', '', 'Take the slices of wholemeal bread & grate into crumbs. Grate lime zest, then juice the lime. Place zest, juice and remaining ingredients in a bowl, season with pepper. Mix well before shaping into 12 thick cakes. Cook in a greased non-stick pan until heated through', '2013-03-01'),
(54, 1, 'Feta & Beetroot Salad', 'BeetrootFetaSalad.jpg', '', 'Combine first 3 ingredients, season with sea salt and pepper, then drizzle with the fourth ... Super easy, yet still sensational (just how we like our salads)!', '2013-03-01'),
(55, 1, 'Beans with Nutmeg', 'BeanswithNutmeg.jpg', '', 'Partly boil beans for 1 minute then drain. Melt butter in frypan / skillet and add beans, season with nutmeg, sautÃ© over a low heat for 2 to 3 minutes, tossing to coat. Serve the beans drizzled with the yummy buttery sauce.', '2013-03-01'),
(56, 1, 'Healthy Hummus', 'Homemade Hummus.jpg', '', 'Blend all ingredients in a food processor.\r\n\r\nOptional:  Serve with fresh veggie sticks and crackers.', '2013-03-01'),
(57, 1, 'Fruit Tingle Kebabs', 'LD512ff49dd69d0Fruit Kebabs.jpg', '', 'Peel the bananas and cut into 8 pieces. Cut the watermelon into cubes as well as the kiwifruit.\r\n\r\nThread banana, mandarin, watermelon and kiwifruit onto skewers and serve immediately.\r\n\r\nOptional: Serve with flavored yoghurts as dipping sauces, such as natural Greek yoghurt mixed with a dollop of raw honey.', '2013-03-01'),
(58, 1, 'Bang Bang Chicken', 'bangbangchicken.jpg', '', 'Place salad on a long rectangular serving plate. Grill the chicken in the hot sesame oil for 2-3 minutes each side or until tender. Remove from heat and rest to cool before laying on top of the salad. Microwave peanut butter with 2 tbs. of water stirring every 30 seconds until runny. Drizzle over chicken and salad. Optional: Can use Chinese cabbage, finely shredded and a variety of freshly chopped vegetables for extra texture and colour.', '2013-03-01'),
(59, 1, 'Creamy Nachos', '4I-Sharchos.jpg', '', 'Recommended to add 1 to 2 tablespoons of sweet chilli sauce to the baked beans.\r\n\r\nIn a serving dish spread the cream cheese, top with the baked beans and sprinkle with cheese. Place under the grill until the cheese bubbles and is golden brown in colour. Serve warm with corn chips and Enjoy!', '2013-03-01'),
(61, 11, 'Piko Piko Flamed Fish', 'image.jpg', 'Bush flamed fish, seasoned with kawakawa horopito and lemon.', 'Step 1: Butterfly fish with a sharp filleting knife along the spine separating the meat from the spine on both sides. Using a sharp set of kitchen scissors cut the spine at the head and tail. Place fish into a fireproof dish (preferably clay)\r\n\r\nStep2: In a mixing bowl combine Piko Piko, and watercress, drizzle a small amount of porter on this and season with horopito and kawakawa\r\n\r\nStep 3:Stuff fish with ingredients from step 2, including sliced lemon\r\n\r\nStep 4: combine salt, pepper, horopito and kawakawa in a mortar and pestle grind until fine.\r\n\r\nStep 5: layer mix from step 4 onto the outside of skin of fish, be generous as the more of this layer on the skin the crunchier it will be. Do not over season either.\r\n\r\nStep 6: fill fireproof dish with a good amount of smokey porter beer, place lid on fireproof dish and place onto of fire (use bbq or oven if cannot use fire) until fish white, tender and cooked through.\r\n\r\nStep 7:  Once done pull spine away from cooked meat leaving behind boneless fillets, serve fish on a bed of fresh watercress, with liquid left behind as drizzle sauce and piko piko as a garnish.\r\n\r\nBellissimo!', '2013-03-03');

-- --------------------------------------------------------

--
-- Table structure for table `recipeingredients`
--

CREATE TABLE IF NOT EXISTS `recipeingredients` (
  `recipeIngredientID` int(11) NOT NULL AUTO_INCREMENT,
  `recipeID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `ingredientQuantity` int(11) NOT NULL,
  `measurementID` int(11) NOT NULL,
  `extraIngredientInfo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`recipeIngredientID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=402 ;

--
-- Dumping data for table `recipeingredients`
--

INSERT INTO `recipeingredients` (`recipeIngredientID`, `recipeID`, `ingredientID`, `ingredientQuantity`, `measurementID`, `extraIngredientInfo`) VALUES
(65, 18, 5, 1, 1, ''),
(64, 18, 42, 2, 0, ''),
(270, 17, 8, 1, 1, ''),
(269, 17, 38, 1, 0, ''),
(268, 17, 25, 1, 0, ''),
(267, 17, 39, 1, 0, ''),
(66, 18, 19, 1, 1, ''),
(78, 21, 6, 2, 1, ''),
(77, 21, 1, 4, 0, ''),
(76, 21, 48, 420, 5, ''),
(75, 20, 16, 16, 0, ''),
(74, 20, 46, 1, 0, ''),
(73, 20, 44, 1, 0, ''),
(72, 20, 47, 1, 0, ''),
(71, 19, 45, 3, 4, ''),
(70, 19, 44, 1, 0, ''),
(69, 19, 11, 1, 0, ''),
(68, 19, 43, 1, 0, ''),
(67, 18, 13, 2, 1, ''),
(113, 23, 51, 4, 0, ''),
(112, 23, 18, 200, 5, ''),
(111, 23, 52, 3, 3, ''),
(110, 23, 4, 1, 3, ''),
(109, 23, 53, 2, 3, ''),
(266, 17, 37, 400, 5, ''),
(259, 37, 81, 200, 5, ''),
(260, 37, 82, 2, 4, ''),
(261, 37, 83, 100, 5, ''),
(262, 38, 3, 3, 4, ''),
(263, 38, 5, 1, 1, ''),
(264, 38, 84, 1, 4, ''),
(265, 38, 85, 4, 1, ''),
(271, 39, 88, 1, 8, ''),
(272, 39, 1, 4, 0, 'Lightly Beaten'),
(273, 39, 89, 2, 0, 'Finely Chopped'),
(274, 39, 90, 2, 10, 'of Chopped and Lightly Fried'),
(275, 40, 91, 12, 0, ''),
(276, 40, 84, 2, 4, ''),
(277, 40, 92, 2, 4, ''),
(278, 41, 82, 1, 1, ''),
(279, 41, 93, 1, 1, ''),
(280, 41, 94, 1, 3, ''),
(281, 41, 1, 1, 0, ''),
(282, 42, 95, 3, 0, 'Juiced'),
(283, 42, 96, 1, 1, 'Halved'),
(284, 42, 97, 3, 1, ''),
(285, 42, 42, 1, 0, 'Frozen & Sliced'),
(286, 43, 98, 8, 0, ''),
(287, 43, 99, 2, 1, 'Shredded'),
(288, 43, 100, 1, 1, ''),
(289, 43, 101, 125, 5, 'Grated'),
(290, 44, 103, 1, 1, ''),
(291, 44, 85, 2, 1, 'Ground'),
(292, 44, 104, 1, 1, 'Grated'),
(293, 44, 102, 750, 5, 'Thickly Sliced'),
(294, 45, 105, 400, 5, ''),
(295, 45, 37, 500, 5, 'Organic'),
(296, 45, 104, 125, 5, 'Grated'),
(297, 45, 106, 1, 1, 'Chopped'),
(298, 46, 107, 1, 0, 'Sheet'),
(299, 46, 90, 2, 10, ''),
(300, 46, 108, 2, 4, ''),
(301, 46, 104, 1, 1, 'Grated'),
(302, 47, 102, 2, 0, ''),
(303, 47, 18, 1, 1, ''),
(304, 47, 19, 1, 1, ''),
(305, 47, 3, 1, 4, 'Melted'),
(306, 48, 109, 2, 1, 'Short Grain'),
(307, 48, 5, 1, 1, ''),
(308, 48, 2, 1, 12, ''),
(309, 48, 3, 2, 4, ''),
(310, 49, 110, 3, 1, ''),
(311, 49, 111, 250, 2, ''),
(312, 49, 1, 1, 0, ''),
(313, 49, 3, 1, 1, 'Softened'),
(314, 50, 25, 6, 0, 'Peeled & Cut Lengthways'),
(315, 50, 113, 1, 3, ''),
(316, 50, 3, 2, 4, ''),
(324, 52, 115, 2, 4, 'Dry'),
(323, 52, 95, 1, 0, ''),
(322, 52, 114, 4, 0, 'Fresh White'),
(325, 52, 3, 3, 4, ''),
(326, 53, 117, 1, 0, ''),
(327, 53, 118, 400, 5, 'Pink Dried'),
(328, 53, 1, 1, 0, ''),
(329, 53, 119, 3, 13, 'Wholemeal'),
(330, 54, 120, 1, 1, 'Crumbed'),
(331, 54, 121, 1, 1, 'Diced'),
(332, 54, 122, 1, 8, ''),
(333, 54, 123, 2, 4, 'Caramelised'),
(334, 55, 124, 200, 5, ''),
(335, 55, 3, 2, 4, ''),
(336, 55, 125, 1, 3, ''),
(337, 56, 126, 300, 5, 'Can'),
(338, 56, 127, 1, 14, 'Crushed'),
(339, 56, 112, 2, 4, ''),
(340, 56, 128, 1, 4, ''),
(341, 57, 42, 2, 0, 'Medium'),
(342, 57, 129, 2, 0, 'Peeled'),
(343, 57, 131, 1, 0, 'Small'),
(344, 57, 132, 2, 0, 'Medium'),
(345, 58, 122, 120, 5, 'Mixed'),
(346, 58, 102, 2, 0, 'Sliced'),
(347, 58, 133, 1, 1, ''),
(348, 58, 82, 1, 1, ''),
(349, 59, 134, 250, 5, ''),
(350, 59, 135, 420, 5, 'Tin'),
(351, 59, 101, 3, 1, 'Grated'),
(352, 59, 136, 1, 8, ''),
(354, 61, 137, 1, 15, 'Buterflied'),
(355, 61, 139, 2, 4, ''),
(356, 61, 140, 3, 4, ''),
(357, 61, 141, 7, 13, ''),
(358, 61, 138, 3, 1, ''),
(359, 61, 142, 2, 7, 'Smokey Porter'),
(360, 61, 143, 8, 13, 'Whole'),
(361, 61, 4, 3, 4, 'Ground'),
(362, 61, 53, 2, 4, 'Ground');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) NOT NULL,
  `userPassword` varchar(100) NOT NULL,
  `userAccess` enum('admin','user') NOT NULL,
  `userFirstName` varchar(100) NOT NULL,
  `userLastName` varchar(100) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userJoinDate` varchar(10) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userName`, `userPassword`, `userAccess`, `userFirstName`, `userLastName`, `userEmail`, `userJoinDate`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin', 'Gavin', 'McGruddy', 'GM@blabla.com', ''),
(8, 'Bob', '48181acd22b3edaebc8a447868a7df7ce629920a', 'user', 'Bob', 'Down', 'Bob.Down@hotmail.com', '2012-12-12'),
(10, 'Jack', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'user', 'Jack', 'Thompson', 'J.Thompson@hotmail.com', '2012-12-12'),
(11, 'MBok', '0ad26b2cd6d84af7a4e790da0172ffea098efbde', 'user', 'Chef', 'Marcus', 'marcus.bok@live.com', '2012-12-16'),
(13, 'Ben ja man', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'user', 'Benjamin', 'Harding', 'ben.harding@yoobee.net.nz', '2013-01-11'),
(14, 'jeremy', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'user', 'Jeremy', 'Bates', 'jermz55555@gmail.com', '2013-01-31'),
(17, 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'user', 'Test', 'O''neal', 'test@xx.xx', '2013-03-13'),
(18, 'Karen', 'ea273f4e5b4f328430248ad0239a2fd06e03f9d2', 'user', 'Karen', 'McGruddy', 'k.mcgruddy@xtra.co.nz', '2013-04-26');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
