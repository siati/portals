function countyChanged(county, subcounty, subcountyField, subcountyUrl, constituency, constituencyField, constituencyUrl) {
    dynamicSubcounties(county, subcounty, subcountyField, subcountyUrl);
    dynamicConstituencies(county, constituency, constituencyField, constituencyUrl);
}

function dynamicSubcounties(county, subcounty, subcountyField, url) {
    $.post(url, {'county': county, 'subcounty': subcounty},
            function (subcounties) {
                subcountyField.html(subcounties).change().find('[value=' + subcounty + ']').parent().val(subcounty);
            }
    );
}

function dynamicConstituencies(county, constituency, constituencyField, url) {
    $.post(url, {'county': county, 'constituency': constituency},
            function (constituencies) {
                constituencyField.html(constituencies).change().find('[value=' + constituency + ']').parent().val(constituency);
            }
    );
}

function dynamicWards(constituency, ward, wardField, url) {
    $.post(url, {'constituency': constituency, 'ward': ward},
            function (wards) {
                wardField.html(wards).find('[value=' + ward + ']').parent().val(ward);
            }
    );
}