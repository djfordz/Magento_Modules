<?php
  
class Filofax_Affiliate_Model_Source_Awincategory
{
	/**
     * Prepare and return array of attributes names.
     */
    public function getAllOptions()
    {
    	$data = $this->getCategoryList();
    	$level_twos = array();
    	
    	$options[] = array('value' => 0,'label' => 'Please Select');
    	
    	foreach($data as $item){
    		if($item[3]>0){
    			if(in_array($item[3], $level_twos)){
		    		$label = ' . . . . . . '.$item[1];
		    	} else {
		    		$label = ' . . . '.$item[1];
		    		$level_twos[] = $item[0];			    	
		    	}
    		} else {
	    		$label = ' . ' . $item[1];
	    		$level_ones[] = $item[0];
    		}
	   	    $options[] = array('value'=>$item[1], 'label'=>$label);
	   	}
	    
        return $options;
    }	

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
    
    public function getCategoryList()
    {
	    $category_list = "4,Electronics,Electronics,0,0
5,Audio Equipment,Audio Equipment,4,0
6,Home Entertainment,Home Entertainment,4,0
7,Photography,Photography,4,0
8,Portable Audio,Portable Audio,4,0
9,Televisions,Televisions,4,0
10,Amplifiers & Receivers,Amplifiers & Receivers,5,0
11,Audio Systems,Audio Systems,5,0
12,Cassette Decks,Cassette Decks,5,0
13,CD Players,CD Players,5,0
14,Radios,Radios,5,0
15,HiFi Speakers,HiFi Speakers,5,0
17,MiniDisc Separates,MiniDisc Separates,5,0
18,Tuners,Tuners,5,0
19,DJ Equipment,DJ Equipment,5,0
20,DVD Players,DVD Players,6,0
21,DVD Recorders,DVD Recorders,6,0
22,Headphones,Headphones,6,0
23,Home Cinema,Home Cinema,6,0
24,Projectors,Projectors,6,0
25,Remote Controls,Remote Controls,6,0
26,Set Top Boxes & Receivers,Set Top Boxes & Receivers,6,0
27,VCR Players,VCR Players,6,0
29,Camcorders,Camcorders,7,0
30,Accessories,Accessories,7,0
32,Cameras,Cameras,7,0
33,Memory,Memory,61,0
34,Photo Development,Photo Development,7,0
35,Accessories,Accessories,8,0
37,Portable Cassette Players,Portable Cassette Players,8,0
38,Portable CD Players,Portable CD Players,8,0
39,Portable MiniDisc,Portable MiniDisc,8,0
40,MP3 Players,MP3 Players,8,0
42,Portable Radios,Portable Radios,8,0
43,Stereos,Stereos,8,0
44,Standard (4:3) TV,Standard (4:3) TV,9,0
45,Combi TVs,Combi TVs,9,0
46,LCD TV,LCD TV,9,0
47,Plasma TV,Plasma TV,9,0
48,Projection TV,Projection TV,9,0
49,Projector Accessories,Projector Accessories,9,0
50,Television Accessories,Television Accessories,9,0
51,Widescreen TV,Widescreen TV,9,0
52,Other Memory,Other Memory,33,0
53,Memory Card Reader,Memory Card Reader,33,0
54,Compact Flash,Compact Flash,33,0
55,Microdrive,Microdrive,33,0
56,XD Cards,XD Cards,33,0
57,Memory Stick,Memory Stick,33,0
58,Multimedia Card,Multimedia Card,33,0
59,SmartMedia,SmartMedia,33,0
60,SD Card,SD Card,33,0
61,Computers & Software,Computers & Software,0,0
62,Computer Components,Computer Components,61,0
63,Handhelds,Handhelds,61,0
64,Hardware,Hardware,61,0
65,Input Devices,Input Devices,61,0
66,Peripherals,Peripherals,61,0
67,ISP & Hosting,ISP & Hosting,61,0
68,Software,Software,61,0
69,Storage Media,Storage Media,61,0
70,Wireless & Networking,Wireless & Networking,61,0
71,CPUs,CPUs,62,0
72,Computer Cases,Computer Cases,62,0
73,Controller Cards,Controller Cards,62,0
74,Fans,Fans,62,0
75,Graphics Cards,Graphics Cards,62,0
76,Motherboards,Motherboards,62,0
77,Power Supplies,Power Supplies,62,0
78,Sound Cards,Sound Cards,62,0
79,TV Cards,TV Cards,62,0
80,GPS & Sat Nav,GPS & Sat Nav,63,0
82,PDAs & Accessories,PDAs & Accessories,63,0
83,Computers,Computers,64,0
84,Laptops,Laptops,64,0
85,Monitors,Monitors,64,0
86,Headsets,Headsets,65,0
87,Joysticks and Gaming,Joysticks and Gaming,65,0
88,Keyboards,Keyboards,65,0
89,Microphones,Microphones,65,0
90,Mouse,Mouse,65,0
91,WebCams,WebCams,65,0
92,Broadband,Broadband,67,0
94,Web Hosting,Web Hosting,67,0
97,Clothing & Accessories,Clothing & Accessories,0,0
98,Children's Clothing,Children's Clothing,97,0
99,Health & Beauty,Health & Beauty,0,0
100,Bodycare & Fitness,Bodycare & Fitness,99,0
101,Bodycare Appliances,Bodycare Appliances,100,0
107,Shaving,Shaving,100,0
110,Cosmetics & Skincare,Cosmetics & Skincare,99,0
111,Cosmetics,Cosmetics,110,0
113,Fragrance,Fragrance,110,0
114,Skincare,Skincare,110,0
115,Haircare,Haircare,99,0
116,Haircare Appliances,Haircare Appliances,115,0
118,Haircare Products,Haircare Products,115,0
121,Health,Health,99,0
122,Contact Lenses,Contact Lenses,121,0
123,Nutrition,Nutrition,121,0
125,Vitamins & Supplements,Vitamins & Supplements,121,0
127,Diet,Diet,121,0
128,All-In-One Printers,All-In-One Printers,66,0
129,Clothing Accessories,Clothing Accessories,97,0
130,Cables,Cables,66,0
133,Computer Speakers,Computer Speakers,66,0
135,Lingerie,Lingerie,97,1
137,Men's Clothing,Men's Clothing,97,0
139,Shoes,Shoes,97,0
141,Women's Clothing,Women's Clothing,97,0
142,Baby Clothes,Baby Clothes,98,0
144,Boys' Clothes,Boys' Clothes,98,0
146,Girls' Clothes,Girls' Clothes,98,0
147,Men's Accessories,Men's Accessories,129,0
149,Women's Accessories,Women's Accessories,129,0
159,Combination Sets,Combination Sets,135,1
161,Socks & Hosiery,Socks & Hosiery,135,1
163,Bodies,Bodies,135,1
167,Suspenders & Garters,Suspenders & Garters,135,1
168,Bras,Bras,135,1
169,Nightwear,Nightwear,135,1
170,Women's Underwear,Women's Underwear,135,1
171,Men's Outerwear,Men's Outerwear,137,0
172,Men's Underwear,Men's Underwear,137,0
173,Gifts, Gadgets & Toys,Gifts, Gadgets & Toys,0,0
174,Men's Sportswear,Men's Sportswear,137,0
175,Men's Trousers,Men's Trousers,137,0
177,Gadgets,Gadgets,173,0
178,Men's Swimwear,Men's Swimwear,137,0
179,Men's Tops,Men's Tops,137,0
181,Gifts,Gifts,173,0
183,Men's Suits,Men's Suits,137,0
187,Toys,Toys,173,0
188,Auctions,Auctions,177,0
189,Men's Footwear,Men's Footwear,139,0
193,Experiences,Experiences,177,0
194,Women's Footwear,Women's Footwear,139,0
196,Adult Toys,Adult Toys,177,1
198,Women's Dresses & Skirts,Women's Dresses & Skirts,141,0
199,Women's Swimwear,Women's Swimwear,141,1
201,Women's Trousers,Women's Trousers,141,0
203,Women's Sportswear,Women's Sportswear,141,0
204,Women's Tops,Women's Tops,141,0
205,Maternity,Maternity,141,0
206,Women's Outerwear,Women's Outerwear,141,0
207,Modems,Modems,66,0
208,Women's Suits,Women's Suits,141,0
209,Printer Consumables,Printer Consumables,66,0
210,Printers,Printers,66,0
211,Scanners,Scanners,66,0
212,Laptop Bags,Laptop Bags,66,0
213,Blank Media,Blank Media,69,0
214,CD Writers,CD Writers,69,0
215,CD-ROM Drives,CD-ROM Drives,69,0
216,DAT Drives,DAT Drives,69,0
217,DVD Drives,DVD Drives,69,0
218,DVD Writers,DVD Writers,69,0
219,Floppy Disk Drives,Floppy Disk Drives,69,0
220,Hard Drives,Hard Drives,69,0
221,Pen Drives,Pen Drives,69,0
222,Tape Drives,Tape Drives,69,0
223,Zip Drives,Zip Drives,69,0
224,Bluetooth Adapters,Bluetooth Adapters,70,0
225,Hubs & Switches,Hubs & Switches,70,0
226,Networking,Networking,70,0
227,Streaming Media Devices,Streaming Media Devices,70,0
228,Wireless Adapters,Wireless Adapters,70,0
229,Wireless Routers,Wireless Routers,70,0
230,Books & Subscriptions,Books & Subscriptions,634,0
231,Audio Books,Audio Books,230,0
232,Bookclubs,Bookclubs,230,0
233,Magazine Subscriptions,Magazine Subscriptions,230,0
235,DVD & Video,DVD & Video,0634,0
236,DVD Rentals,DVD Rentals,235,0
237,UMDs,UMDs,235,0
238,Adult DVDs & Video,Adult DVDs & Video,235,0
239,VHS Videos,VHS Videos,235,0
240,DVDs,DVDs,235,0
241,Music,Music,634,0
242,Musical Instruments,Musical Instruments,241,0
244,Music Downloads,Music Downloads,241,0
245,CDs,CDs,241,0
246,Sports,Sports,0,0
247,Fitness,Fitness,246,0
248,Other Sports,Other Sports,246,0
249,Racket Sports,Racket Sports,246,0
250,Team Sports,Team Sports,246,0
251,Sportswear & Swimwear,Sportswear & Swimwear,246,0
252,Cycling,Cycling,247,0
255,Sports Supports,Sports Supports,247,0
256,Boxing,Boxing,248,0
258,Fishing,Fishing,248,0
259,Golf,Golf,248,0
260,Outdoor Adventure,Outdoor Adventure,248,0
261,Snooker, Pool and Billiards,Snooker, Pool and Billiards,248,0
262,Trampolines,Trampolines,248,0
265,Darts,Darts,248,0
266,Badminton,Badminton,249,0
267,Squash,Squash,249,0
268,Table Tennis,Table Tennis,249,0
269,Tennis,Tennis,249,0
270,Cricket,Cricket,250,0
271,Football,Football,250,0
272,Basketball,Basketball,250,0
273,Hockey,Hockey,250,0
277,Swimming Accessories,Swimming Accessories,251,0
281,Finance,Finance,0,0
282,Loans,Loans,281,0
283,Credit Cards,Credit Cards,281,0
285,Insurance,Insurance,281,0
286,Investments,Investments,281,0
287,Mortgages,Mortgages,281,0
288,Personal Banking,Personal Banking,281,0
289,Bad Credit Loans,Bad Credit Loans,282,0
290,Finance,Finance,282,0
291,Home Loans,Home Loans,282,0
292,Personal Loans,Personal Loans,282,0
293,Secured Loans,Secured Loans,282,0
294,Car Loans,Car Loans,282,0
296,Unsecured Loans,Unsecured Loans,282,0
297,Cashback & Reward Credit Cards,Cashback & Reward Credit Cards,283,0
298,Credit Cards,Credit Cards,283,0
299,Health Insurance,Health Insurance,285,0
300,Home Insurance,Home Insurance,285,0
301,Life Insurance,Life Insurance,285,0
302,Mortgage Insurance,Mortgage Insurance,285,0
303,Travel Insurance,Travel Insurance,285,0
304,Vehicle Insurance,Vehicle Insurance,285,0
305,Bonds,Bonds,286,0
306,Cash ISAs,Cash ISAs,286,0
307,Pensions,Pensions,286,0
308,Quotes,Quotes,286,0
309,Shares,Shares,286,0
310,Stockbrokers,Stockbrokers,286,0
311,Stocks & Shares ISAs,Stocks & Shares ISAs,286,0
312,Bad Credit Mortgages,Bad Credit Mortgages,287,0
313,Buy to Let Mortgages,Buy to Let Mortgages,287,0
314,Flexible Mortgages,Flexible Mortgages,287,0
315,Mortgage Advice,Mortgage Advice,287,0
316,Mortgage Insurance,Mortgage Insurance,287,0
317,Mortgage Protection,Mortgage Protection,287,0
318,Remortgages,Remortgages,287,0
319,Self Certification Mortgages,Self Certification Mortgages,287,0
320,Standard Variable Mortgages,Standard Variable Mortgages,287,0
321,Discounted Mortgages,Discounted Mortgages,287,0
322,Capped Mortgages,Capped Mortgages,287,0
323,Fixed Rate Mortgages,Fixed Rate Mortgages,287,0
324,Bank Accounts,Bank Accounts,288,0
325,Current Accounts,Current Accounts,288,0
326,Online Banking,Online Banking,288,0
327,Savings Accounts,Savings Accounts,288,0
328,Travel,Travel,0,0
329,Airport Parking,Airport Parking,328,0
330,Car Hire,Car Hire,328,0
333,Cruises,Cruises,328,0
335,Ferries & Eurotunnel,Ferries & Eurotunnel,328,0
336,Flights,Flights,328,0
338,Holidays,Holidays,328,0
347,Telephones & Faxes,Telephones & Faxes,0,0
348,Mobile Phones,Mobile Phones,347,0
349,Other Telephones,Other Telephones,347,0
350,Mobile Phone Accessories,Mobile Phone Accessories,348,0
351,Pay Monthly/Contract Phones,Pay Monthly/Contract Phones,348,0
352,Prepay Mobile Phones,Prepay Mobile Phones,348,0
353,Ringtones & Logos,Ringtones & Logos,348,0
354,Simfree/Handset Only Phones,Simfree/Handset Only Phones,348,0
355,All-In-Ones,All-In-Ones,349,0
356,Fax Machines,Fax Machines,349,0
357,Home Telephone Accessories,Home Telephone Accessories,349,0
358,House Telephones,House Telephones,349,0
359,IP Phones,IP Phones,349,0
360,Video Conferencing,Video Conferencing,349,0
361,Home Appliances,Home Appliances,0,0
362,Cooking,Cooking,361,0
363,Laundry & Cleaning,Laundry & Cleaning,361,0
364,Refrigeration,Refrigeration,361,0
365,Small Appliances,Small Appliances,361,0
366,Cooker Hoods,Cooker Hoods,362,0
367,Cookers & Ovens,Cookers & Ovens,362,0
368,Grills,Grills,362,0
369,Microwaves,Microwaves,362,0
371,Hobs,Hobs,362,0
372,Dishwashers,Dishwashers,363,0
373,Irons,Irons,363,0
374,Tumble Dryers,Tumble Dryers,363,0
375,Vacuum Cleaners,Vacuum Cleaners,363,0
377,Vacuum Cleaner Accessories,Vacuum Cleaner Accessories,363,0
378,Freezers,Freezers,364,0
379,Electronic Gadgets,Electronic Gadgets,177,0
380,Fridge Freezers,Fridge Freezers,364,0
381,Fridges,Fridges,364,0
383,Blenders,Blenders,365,0
384,Anniversary Gifts,Anniversary Gifts,181,0
385,Breadmakers,Breadmakers,365,0
386,Can Openers,Can Openers,365,0
387,Birthday Gifts,Birthday Gifts,181,0
390,Coffee Makers,Coffee Makers,365,0
391,Decorations,Decorations,181,0
392,Deep Fryers,Deep Fryers,365,0
393,Flowers,Flowers,181,0
394,Electric Kettles,Electric Kettles,365,0
395,Hampers,Hampers,181,0
396,Electric Shavers,Electric Shavers,365,0
397,Food Processors,Food Processors,365,0
398,Magazine Subscriptions,Magazine Subscriptions,181,0
399,Ice Cream Makers,Ice Cream Makers,365,0
402,Juicers,Juicers,365,0
404,Other Appliances,Other Appliances,365,0
405,Wedding Gifts,Wedding Gifts,181,0
406,Sandwich / Waffle Makers,Sandwich / Waffle Makers,365,0
407,Toasters,Toasters,365,0
411,Action Figures,Action Figures,187,0
412,Baby Toys,Baby Toys,187,0
413,Creative & Construction,Creative & Construction,187,0
414,Dolls,Dolls,187,0
415,Electronic & Radio Controlled Toys,Electronic & Radio Controlled Toys,187,0
416,Games, Puzzles & Learning,Games, Puzzles & Learning,187,0
417,Musical Toys,Musical Toys,187,0
418,Outdoor Toys,Outdoor Toys,187,0
419,Soft Toys,Soft Toys,187,0
420,Toy Models,Toy Models,187,0
421,Home & Garden,Home & Garden,0,0
422,Bathrooms And Accessories,Bathrooms And Accessories,421,0
423,Food,Food,421,0
424,Furniture,Furniture,421,0
425,Garden,Garden,421,0
426,General Household,General Household,421,0
427,Home Accessories,Home Accessories,421,0
428,DIY,DIY,421,0
429,Household Bills,Household Bills,421,0
430,Kitchen,Kitchen,421,0
431,Property,Property,421,0
432,Wine, Spirits & Tobacco,Wine, Spirits & Tobacco,421,0
433,Bathroom Scales,Bathroom Scales,422,0
434,Bathrooms,Bathrooms,422,0
435,Bathtubs,Bathtubs,422,0
436,Bespoke Bathrooms,Bespoke Bathrooms,422,0
437,Chocolate,Chocolate,423,0
438,Condiments,Condiments,423,0
440,Dairy,Dairy,423,0
441,Drinks,Drinks,423,0
442,Fruit & Vegetables,Fruit & Vegetables,423,0
444,Meat, Poultry & Fish,Meat, Poultry & Fish,423,0
445,Organic Food,Organic Food,423,0
446,Party Food,Party Food,423,0
447,Ready Meals,Ready Meals,423,0
448,Chairs,Chairs,424,0
449,Sofas,Sofas,424,0
450,Tables,Tables,424,0
451,Beds,Beds,424,0
452,Storage,Storage,424,0
453,Mattresses,Mattresses,424,0
455,Barbecues & Accessories,Barbecues & Accessories,425,0
456,Plants & Seeds,Plants & Seeds,425,0
457,Garden & Leisure,Garden & Leisure,425,0
458,Sheds & Garden Furniture,Sheds & Garden Furniture,425,0
459,Garden Tools,Garden Tools,425,0
460,Lawn Mowers,Lawn Mowers,425,0
463,Flooring & Carpeting,Flooring & Carpeting,426,0
464,Heating & Cooling,Heating & Cooling,426,0
465,Lighting,Lighting,426,0
466,Pets,Pets,426,0
467,Radiators,Radiators,426,0
469,Furniture Accessories,Furniture Accessories,427,0
470,House Accessories,House Accessories,427,0
473,Decorations,Decorations,427,0
474,Hand Tools,Hand Tools,428,0
475,Home Security,Home Security,428,0
476,Painting & Decorating,Painting & Decorating,428,0
477,Power Tools,Power Tools,428,0
478,Telephone Bills,Telephone Bills,429,0
479,Gas & Electric Bills,Gas & Electric Bills,429,0
481,Bespoke Kitchens,Bespoke Kitchens,430,0
483,Crockery,Crockery,430,0
484,Cutlery,Cutlery,430,0
485,Glassware,Glassware,430,0
487,Kitchen Sinks and Taps,Kitchen Sinks and Taps,430,0
488,Kitchen Units,Kitchen Units,430,0
489,Alcoholic Drinks,Alcoholic Drinks,432,0
490,Wine,Wine,432,0
491,Rent Property,Rent Property,431,0
492,Buy Property,Buy Property,431,0
493,Vehicles, Parts and Accessories,Vehicles, Parts & Accessories,0,0
495,Car Accessories,Car Accessories,493,0
496,Custom Number Plates,Custom Number Plates,495,0
507,Car Parts,Car Parts,495,0
521,Video Gaming,Video Gaming,634,0
529,Living Room,Living Room,421,0
530,Bathroom Sinks & Taps,Bathroom Sinks & Taps,422,0
532,Showers,Showers,422,0
533,Sinks,Sinks,422,0
535,Washing Machines,Washing Machines,363,0
536,Washer Dryers,Washer Dryers,363,0
537,Cables, Parts & Power Supplies,Cables, Parts & Power Supplies,5,0
538,Books,Books,230,0
539,Children's Accessories,Children's Accessories,129,0
540,Jewellery,Jewellery,0,0
542,Men's Jewellery,Men's Jewellery,540,0
544,Men's Watches,Men's Watches,540,0
546,Women's Jewellery,Women's Jewellery,540,0
547,Women's Watches,Women's Watches,540,0
548,Men's Socks,Men's Socks,137,0
549,Film Downloads,Film Downloads,235,0
550,Blu-Ray,Blu-Ray,235,0
551,Blu-Ray Players,Blu-Ray Players,6,0
553,HD DVD Players,HD DVD Players,6,0
554,Pre-pay Credit Cards,Pre-pay Credit Cards,283,0
555,Specialist Insurance,Specialist Insurance,285,0
556,Cassettes & Vinyl,Cassettes & Vinyl,241,0
557,Water Sports,Water Sports,248,0
558,Extreme Sports,Extreme Sports,246,0
559,Weight Training,Weight Training,247,0
560,Winter Sports,Winter Sports,246,0
561,Other Team Sports,Other Team Sports,250,0
563,In-Car Entertainment,In-Car Entertainment,495,0
564,Motorcycles,Motorcycles,493,0
565,New & Used Cars,New & Used Cars,493,0
566,Tyres,Tyres,493,0
567,Vehicle Security,Vehicle Security,493,0
568,Wheels,Wheels,493,0
569,Vehicle Servicing,Vehicle Servicing,493,0
570,Personalised Gifts,Personalised Gifts,181,0
571,Vehicle Finance,Vehicle Finance,493,0
575,Console Accessories,Console Accessories,521,0
576,Console,Console,521,0
577,Retro Games,Retro Games,521,0
579,Video Games,Video Games,521,0
580,Contents Insurance,Contents Insurance,285,0
581,Glasses,Glasses,121,0
583,Vehicle Leasing,Vehicle Leasing,493,0
585,HD DVD,HD DVD,235,0
586,Tickets,Tickets,0,0
588,Theatre,Theatre,586,0
589,Tourist Attractions,Tourist Attractions,586,0
590,Concerts,Concerts,586,0
591,Theme Parks,Theme Parks,586,0
592,Sporting Events,Sporting Events,586,0
593,Equestrian,Equestrian,248,0
594,Oral Health,Oral Health,121,0
595,Bags,Bags,129,0
596,Office Supplies,Office Supplies,421,0
597,Curtains & Blinds,Curtains & Blinds,427,0
598,Christmas Gifts,Christmas Gifts,181,0
599,Baby Products,Baby Products,421,0
600,Valentine's Day,Valentine's Day,181,0
602,Other Occasions,Other Occasions,181,0
603,RAM--,RAM--,33,0
604,Arts & Crafts,Arts & Crafts,421,0
605,Agricultural Products,Agricultural Products,421,0
606,Tobacco,Tobacco,432,0
607,Tinned Food,Tinned Food,423,0
608,Cakes, Snacks & Sweets,Cakes, Snacks & Sweets,423,0
609,Adult Books,Adult Books,230,1
611,Collectibles,Collectibles,187,0
612,Sports Memorabilia,Sports Memorabilia,246,0
613,Fancy Dress,Fancy Dress,97,0
614,Children's Footwear,Children's Footwear,139,0
615,Cookware & Utensils,Cookware & Utensils,430,0
616,Cleaning,Cleaning,426,0
617,Home Textiles,Home Textiles,427,0
618,Batteries,Batteries,8,0
619,Optical Devices,Optical Devices,7,0
620,Dating,Dating,173,0
623,Novelty T-Shirts,Novelty T-Shirts,97,0
624,Medical,Medical,121,0
625,Art,Art,427,0
626,General Clothing,General Clothing,97,0
627,General Clothing,General Clothing,0,0
629,Dating,Dating,173,0
631,Novelty T-Shirts,Novelty T-Shirts,181,0
632,Motorsport,Motorsport,248,0
633,Appliance Spares,Appliance Spares,361,0
634,Entertainment,Entertainment,0,0
635,Auto Care,Auto Care,493,0
637,Driving,Driving,193,0
639,Extreme,Extreme,193,0
640,Flying,Flying,193,0
641,Holiday,Holiday,193,0
642,Lifestyle,Lifestyle,193,0
643,Other Experiences,Other Experiences,193,0
644,Pampering,Pampering,193,0
645,Adult Gifts,Adult Gifts,181,1
646,Calendars,Calendars,181,0
647,Greeting Cards,Greeting Cards,181,0
648,Other Gadgets,Other Gadgets,177,0
649,Other Toys,Other Toys,187,0
650,Water Experiences,Water Experiences,193,0
651,LED TV,LED TV,9,0";

		$line_breaks = explode("\n",$category_list);
		$return = array();

		foreach ($line_breaks as $line_break) {
			$line_break = explode(",", $line_break);
			$return[] = $line_break;
		}
		return $return;
    }
}
