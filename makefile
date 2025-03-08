.PHONY: tag release update-composer

# Update version in composer.json
update-composer:
	sed -i '' 's/"version": "$(VERSION)"/"version": "$(NEW_VERSION)"/' composer.json
	git add composer.json
	git commit -m "Bump version to $(NEW_VERSION)"


# Create a new tag
tag: update-composer
	git tag $(NEW_VERSION)
	git push origin $(NEW_VERSION)

# Create a GitHub release (requires GitHub CLI)
release: tag
	gh release create $(NEW_VERSION) --title "Release $(NEW_VERSION)" --notes "New release $(NEW_VERSION)"
