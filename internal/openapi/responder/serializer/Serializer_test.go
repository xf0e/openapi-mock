package serializer

import (
	"testing"

	"github.com/stretchr/testify/assert"
)

func TestNew(t *testing.T) {
	serializer := New()

	assert.IsType(t, &rawSerializer{}, serializer.(*coordinatingSerializer).formatSerializers["raw"])
	assert.IsType(t, &jsonSerializer{}, serializer.(*coordinatingSerializer).formatSerializers["json"])
	assert.IsType(t, &xmlSerializer{}, serializer.(*coordinatingSerializer).formatSerializers["xml"])
}
